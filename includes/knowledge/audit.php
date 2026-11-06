<?php
/**
 * The Knowledge history — the ONE place a row is written.
 *
 * Four files had grown their own copy of the same INSERT before this existed
 * (folders.php, permissions.php, permission_model.php, visibility.php), which is
 * the same duplication the visibility clause suffered from: nothing breaks when
 * the copies drift, one of them just quietly starts recording something slightly
 * different — and an audit trail that disagrees with itself is worse than none,
 * because it is trusted.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * BEST EFFORT, ALWAYS
 * An unwritable log must never fail the thing being logged. Refusing to save an
 * article because its history row would not write is a worse outcome than a gap
 * in the history — but the gap is logged to error_log, because a silently
 * unwritable audit table is the one failure nobody would ever notice.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * ⚠️ VIEWS ARE A DIFFERENT VOLUME CLASS FROM EVERYTHING ELSE
 * knowledge_articles.view_count already counts every read. A row per view on a
 * busy knowledge base is millions a year, and the rows people actually come
 * looking for — who changed the permissions, who deleted it — would be buried
 * under them within a month.
 *
 * So a view is recorded ONCE PER PERSON PER ARTICLE PER DAY. That keeps the
 * question worth asking ("who has read this?") answerable, while a hundred
 * re-reads by the same analyst in an afternoon stay one row. The total count is
 * still exact, because it comes from view_count and never from here.
 * ─────────────────────────────────────────────────────────────────────────────
 */

/** Actions worth distinguishing. Anything else is refused rather than stored. */
const KNOWLEDGE_AUDIT_ACTIONS = [
    'create', 'edit', 'view', 'delete', 'restore', 'purge',
    'move', 'permissions', 'admin_override',
];

/**
 * Record one thing that happened.
 *
 * @param string     $type    'article' | 'folder'
 * @param int        $id      the object it happened to
 * @param string     $action  one of KNOWLEDGE_AUDIT_ACTIONS
 * @param ?int       $analyst the analyst responsible, or null
 * @param ?array     $detail  anything worth keeping; stored as JSON
 * @param ?int       $user    a portal user, when the actor is not an analyst
 */
function knowledgeAuditLog(PDO $conn, string $type, int $id, string $action,
                           ?int $analyst = null, ?array $detail = null, ?int $user = null): void
{
    if (!in_array($action, KNOWLEDGE_AUDIT_ACTIONS, true)) {
        // A typo'd action would be stored happily and then match nothing when
        // somebody searched for it — a confident silence, which is the failure
        // mode this whole module keeps running into. Refuse it loudly instead.
        error_log('knowledge audit: refusing unknown action "' . $action . '"');
        return;
    }
    try {
        $conn->prepare(
            "INSERT INTO knowledge_audit (object_type, object_id, action, analyst_id, user_id, detail, ip_address)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        )->execute([
            $type, $id, $action, $analyst, $user,
            $detail === null ? null : json_encode($detail),
            $_SERVER['REMOTE_ADDR'] ?? null,
        ]);
    } catch (PDOException $e) {
        error_log('knowledge audit: could not record ' . $action . ' on ' . $type . ' ' . $id . ' — ' . $e->getMessage());
    }
}

/**
 * Record that somebody read an article — at most once per person per day.
 *
 * The de-duplication is a SELECT before the INSERT rather than a unique key on
 * a date column, because the same table holds every other action too and a
 * unique key covering them would stop somebody editing an article twice in one
 * day. The race (two tabs, same second) would at worst write two rows, which is
 * a cosmetic duplicate rather than a wrong answer — the alternative, locking a
 * log table on every article read, is not worth that.
 *
 * @param int $articleId  the knowledge article read; 0 or less records nothing
 * @param ?int $analyst  the analyst who read it, or null for a portal reader
 * @param ?int $user  the portal user who read it, or null for an analyst; both null records nothing
 */
function knowledgeAuditView(PDO $conn, int $articleId, ?int $analyst, ?int $user = null): void
{
    if ($articleId <= 0 || ($analyst === null && $user === null)) return;
    try {
        $st = $conn->prepare(
            "SELECT 1 FROM knowledge_audit
              WHERE object_type = 'article' AND object_id = ? AND action = 'view'
                AND analyst_id <=> ? AND user_id <=> ?
                AND created_datetime >= UTC_DATE()
              LIMIT 1"
        );
        $st->execute([$articleId, $analyst, $user]);
        if ($st->fetchColumn()) return;
    } catch (PDOException) {
        // Cannot tell whether it is already recorded; recording a possible
        // duplicate beats losing the row.
    }
    knowledgeAuditLog($conn, 'article', $articleId, 'view', $analyst, null, $user);
}

/**
 * The history of one object, newest first, with the names filled in.
 *
 * Names are resolved HERE rather than joined, because an analyst who has since
 * been deleted must still show as something — a history whose actor column is
 * blank for everyone who ever left is not a history anybody can use.
 *
 * @param string $type  'article' or 'folder'
 * @param int $id  the article or folder id
 * @param int $limit  the most entries returned, clamped to 1-500
 */
function knowledgeAuditHistory(PDO $conn, string $type, int $id, int $limit = 200): array
{
    $limit = max(1, min(500, $limit));
    $st = $conn->prepare(
        "SELECT id, action, analyst_id, user_id, detail, created_datetime
           FROM knowledge_audit
          WHERE object_type = ? AND object_id = ?
          ORDER BY created_datetime DESC, id DESC
          LIMIT {$limit}"
    );
    $st->execute([$type, $id]);

    $names = [];
    $nameOf = function (?int $aid, ?int $uid) use ($conn, &$names): string {
        if ($aid === null && $uid === null) return '(system)';
        $key = ($aid !== null ? 'a' : 'u') . ($aid ?? $uid);
        if (isset($names[$key])) return $names[$key];
        try {
            if ($aid !== null) {
                $s = $conn->prepare("SELECT full_name FROM analysts WHERE id = ?");
                $s->execute([$aid]);
            } else {
                $s = $conn->prepare("SELECT COALESCE(NULLIF(display_name,''), email, username) FROM users WHERE id = ?");
                $s->execute([$uid]);
            }
            $v = $s->fetchColumn();
        } catch (PDOException) { $v = false; }
        return $names[$key] = ($v === false || $v === null || $v === '') ? '(deleted)' : (string)$v;
    };

    $out = [];
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $out[] = [
            'id'       => (int)$r['id'],
            'action'   => $r['action'],
            'who'      => $nameOf($r['analyst_id'] === null ? null : (int)$r['analyst_id'],
                                  $r['user_id'] === null ? null : (int)$r['user_id']),
            'is_portal'=> $r['analyst_id'] === null && $r['user_id'] !== null,
            'detail'   => $r['detail'] ? json_decode($r['detail'], true) : null,
            'when'     => $r['created_datetime'],
        ];
    }
    return $out;
}
