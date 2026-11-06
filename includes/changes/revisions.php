<?php
/**
 * Change text revisions - every saved version of a change's long-text fields, in full.
 *
 * WHY A TABLE OF ITS OWN
 * ----------------------
 * change_audit holds the short fields, cut to 200 characters. That is fine for a
 * priority; for a 10,000-character rollback plan a truncated "before" is worse than
 * none, because it looks complete. So the long fields were never recorded at all,
 * and an edit simply overwrote the text that had been reviewed. Now each save that
 * changes one writes the WHOLE new value here. The previous version is just the row
 * before it for the same field, so "previous and new" can never disagree.
 *
 * TRAP: revisions are written by ChangesService, inside the same transaction as the
 * UPDATE of the change, never by an adapter. The edit form, the inline editor, the
 * REST API and offboarding all reach the service, so they all leave history behind
 * without knowing it exists. A revision written in an adapter would leave the API
 * without one - the drift the service layer exists to stop.
 *
 * TRAP: rows here are never updated or deleted by any endpoint. There is no update
 * path to protect because there is no update path. Only deleting the change itself
 * removes them (ON DELETE CASCADE), and Part 1 of #180 restricts that.
 *
 * THE BASELINE
 * ------------
 * A change that existed before this table did has text with no recorded past. The
 * first time such a field is touched, ensureBaseline() writes the value AS FOUND,
 * with no editor and source 'upgrade', and it is shown as "as found when history
 * began" - never as the original, never attributed to anybody.
 *
 * Design: wiki Approvals-and-Evidence-2-Change-Text-History.
 */

final class ChangeRevisions
{
    /**
     * The long-text fields that keep a full history: column => [format, English label].
     * The label is what the audit trail shows (change_audit.field_name is English, like
     * every other row there); the UI translates by key.
     */
    public const FIELDS = [
        'description'                => ['html', 'Description'],
        'reason_for_change'          => ['html', 'Reason for change'],
        'risk_evaluation'            => ['html', 'Risk evaluation'],
        'test_plan'                  => ['html', 'Test plan'],
        'rollback_plan'              => ['html', 'Rollback plan'],
        'post_implementation_review' => ['html', 'Post-implementation review'],
        'pir_lessons_learned'        => ['text', 'Lessons learned'],
        'pir_follow_up'              => ['text', 'Follow-up'],
    ];

    /** The audit action_type of a text edit; new_value carries "Revision #<id>". */
    public const AUDIT_ACTION = 'text_edit';

    /**
     * sha256 of a value as stored; NULL hashes as the empty string.
     *
     * @param ?string $content  the field's value
     */
    public static function hash(?string $content): string
    {
        return hash('sha256', $content ?? '');
    }

    /**
     * Write one revision holding the field's whole new value. Returns its id.
     *
     * @param int $changeId  the change edited
     * @param string $field  a key of FIELDS, or 'comment' for a deleted comment
     * @param ?string $content  the value after the save; null when it was cleared
     * @param ?int $editorId  who saved it; null for a baseline
     * @param string $source  ui | api | system | upgrade | deleted
     * @param ?string $at  UTC 'Y-m-d H:i:s' to record instead of now (a deleted comment keeps its own date)
     */
    public static function write(PDO $conn, int $changeId, string $field, ?string $content, ?int $editorId,
                                 string $source, bool $isBaseline = false, ?string $at = null): int
    {
        $format = self::FIELDS[$field][0] ?? 'text';
        $conn->prepare(
            "INSERT INTO change_text_revisions
                (change_id, field_key, content, content_format, content_hash, is_baseline, editor_id, source, created_datetime)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, " . ($at === null ? 'UTC_TIMESTAMP()' : '?') . ")"
        )->execute(array_merge(
            [$changeId, $field, $content, $format, self::hash($content), $isBaseline ? 1 : 0, $editorId, $source],
            $at === null ? [] : [$at]
        ));
        return (int)$conn->lastInsertId();
    }

    /**
     * Before the first recorded edit of a field, record what was there: once per
     * field, only when the field has no revisions yet and holds something. A change
     * created after the upgrade already has its first revision, so it never needs one.
     * Returns the baseline's id, or null when none was needed.
     *
     * @param int $changeId  the change
     * @param string $field  a key of FIELDS
     * @param ?string $current  the value as it stands, before the edit
     */
    public static function ensureBaseline(PDO $conn, int $changeId, string $field, ?string $current): ?int
    {
        if ($current === null || $current === '') {
            return null;   // nothing was there, so there is nothing to lose
        }
        $s = $conn->prepare("SELECT 1 FROM change_text_revisions WHERE change_id = ? AND field_key = ? LIMIT 1");
        $s->execute([$changeId, $field]);
        if ($s->fetchColumn()) {
            return null;
        }
        return self::write($conn, $changeId, $field, $current, null, 'upgrade', true);
    }

    /**
     * The id of the revision that holds a field's CURRENT value, writing a baseline
     * first if the field has no history yet. Used by an approval snapshot to point at
     * exactly what was reviewed. Null when the field is empty and has never been set.
     *
     * @param int $changeId  the change
     * @param string $field  a key of FIELDS
     * @param ?string $current  the field's value as it stands
     */
    public static function currentId(PDO $conn, int $changeId, string $field, ?string $current): ?int
    {
        self::ensureBaseline($conn, $changeId, $field, $current);
        $s = $conn->prepare(
            "SELECT id, content_hash FROM change_text_revisions
              WHERE change_id = ? AND field_key = ? ORDER BY id DESC LIMIT 1"
        );
        $s->execute([$changeId, $field]);
        $row = $s->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        // The latest revision must BE the current value. If the text was changed
        // around the service (a direct SQL edit), say so with a fresh 'system' row
        // rather than point an approval at words that are no longer there.
        if ($row['content_hash'] !== self::hash($current)) {
            return self::write($conn, $changeId, $field, $current, null, 'system');
        }
        return (int)$row['id'];
    }

    /**
     * Every revision of a change, newest first, WITHOUT content - cheap to list.
     *
     * @param int $changeId  the change
     * @param ?string $field  one field key to filter on, or null for all
     */
    public static function listFor(PDO $conn, int $changeId, ?string $field = null): array
    {
        $sql = "SELECT r.id, r.field_key, r.content_format, r.content_hash, r.is_baseline, r.editor_id,
                       a.full_name AS editor_name, r.source, r.created_datetime,
                       COALESCE(CHAR_LENGTH(r.content), 0) AS length
                  FROM change_text_revisions r
                  LEFT JOIN analysts a ON a.id = r.editor_id
                 WHERE r.change_id = ?" . ($field !== null ? ' AND r.field_key = ?' : '') . "
                 ORDER BY r.id DESC";
        $s = $conn->prepare($sql);
        $s->execute($field !== null ? [$changeId, $field] : [$changeId]);
        return array_map([self::class, 'shape'], $s->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * One revision WITH its content, and the id of the revision before it for the
     * same field (null for the first). Null when it does not belong to the change.
     *
     * @param int $changeId  the change it must belong to
     * @param int $revisionId  the revision
     */
    public static function get(PDO $conn, int $changeId, int $revisionId): ?array
    {
        $s = $conn->prepare(
            "SELECT r.*, a.full_name AS editor_name, COALESCE(CHAR_LENGTH(r.content), 0) AS length
               FROM change_text_revisions r
               LEFT JOIN analysts a ON a.id = r.editor_id
              WHERE r.id = ? AND r.change_id = ?"
        );
        $s->execute([$revisionId, $changeId]);
        $row = $s->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        $p = $conn->prepare(
            "SELECT MAX(id) FROM change_text_revisions WHERE change_id = ? AND field_key = ? AND id < ?"
        );
        $p->execute([$changeId, $row['field_key'], $revisionId]);
        $prev = $p->fetchColumn();
        $out = self::shape($row);
        $out['content']     = $row['content'];
        $out['previous_id'] = $prev ? (int)$prev : null;
        return $out;
    }

    /**
     * A revision row in the shape every caller returns.
     *
     * @param array $r  a change_text_revisions row, joined to the editor's name
     */
    private static function shape(array $r): array
    {
        return [
            'id'           => (int)$r['id'],
            'field'        => $r['field_key'],
            'format'       => $r['content_format'],
            'content_hash' => $r['content_hash'],
            'is_baseline'  => (bool)$r['is_baseline'],
            'editor_id'    => $r['editor_id'] !== null ? (int)$r['editor_id'] : null,
            'editor_name'  => $r['editor_name'] ?? null,
            'source'       => $r['source'],
            'created_at'   => $r['created_datetime'],
            'length'       => (int)$r['length'],
        ];
    }
}
