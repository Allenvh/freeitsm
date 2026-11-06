<?php
/**
 * The procedures a task occurrence is carried out against (GH #190 phase 2):
 * references to Knowledge articles, read in the task's Work view through
 * Knowledge's own visibility rules, and frozen when the occurrence is done.
 *
 * 🔴 WHY THE TEXT IS FROZEN. An ordinary Knowledge save edits an article in
 * place; only "save as a new version" keeps the old text. So "done against
 * version 4" cannot promise the words. When an occurrence is completed or
 * skipped, each referenced article's title and body as they stand are kept in
 * task_procedure_snapshots - once per distinct text, keyed by its hash - and the
 * reference points at it. While the occurrence is open it shows the article as
 * it is now, because that is what the analyst is working from.
 *
 * Developer guide: Task-Forms-Developer-Guide (section "Procedures").
 */

require_once __DIR__ . '/../service_context.php';
require_once __DIR__ . '/task_history.php';

class TaskProcedures
{
    /** Tasks -> Settings -> Checklists -> "Procedures on tasks". Off by default. */
    public static function enabled(PDO $conn): bool
    {
        try {
            $s = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'tasks_procedures_enabled'");
            $s->execute();
            return (string)$s->fetchColumn() === '1';
        } catch (Throwable) {
            return false;
        }
    }

    /** Have the tables been created yet (Database Verification)? Cached per request. */
    public static function ready(PDO $conn): bool
    {
        static $ready = null;
        if ($ready !== null) return $ready;
        try {
            $conn->query("SELECT id FROM task_procedures LIMIT 0");
            $conn->query("SELECT id FROM task_procedure_snapshots LIMIT 0");
            return $ready = true;
        } catch (Throwable) {
            return $ready = false;
        }
    }

    /**
     * May this analyst read Knowledge at all? The modules the Knowledge article endpoint itself
     * accepts (knowledge_article.php) - then each article still goes through its visibility rules.
     */
    public static function mayReadKnowledge(PDO $conn, int $analystId): bool
    {
        if ($analystId <= 0 || !function_exists('analystCanAccessModule')) return false;
        // Not while Knowledge itself is switched off for the install (GH #217) -
        // Tickets or LMS access would otherwise still reach its articles.
        if (!moduleIsEnabled($conn, 'knowledge')) return false;
        foreach (['knowledge', 'tickets', 'lms'] as $m) {
            if (analystCanAccessModule($conn, $analystId, $m)) return true;
        }
        return false;
    }

    /** Knowledge's own visibility clause for this analyst, appended to a WHERE on alias `a`. */
    private static function visibility(PDO $conn, int $analystId): array
    {
        require_once __DIR__ . '/../knowledge/visibility.php';
        $viewer = KnowledgeViewer::forAnalyst($conn, $analystId);
        return knowledgeVisibilitySql($conn, $viewer, 'a', ['lifecycle' => 'unarchived']);
    }

    /**
     * A task's procedures for the Work view: each reference, the live article where the
     * viewer may read it (title, body, version), and what it was frozen against.
     */
    public static function forTask(PDO $conn, int $taskId, int $viewerId): array
    {
        if (!self::ready($conn)) return [];
        $st = $conn->prepare("SELECT p.*, s.title AS frozen_title FROM task_procedures p
                                LEFT JOIN task_procedure_snapshots s ON s.id = p.snapshot_id
                               WHERE p.task_id = ? ORDER BY p.sort_order, p.id");
        $st->execute([$taskId]);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
        $mayRead = self::mayReadKnowledge($conn, $viewerId);
        $vis = $mayRead ? self::visibility($conn, $viewerId) : null;
        $out = [];
        foreach ($rows as $r) {
            $article = null;
            if ($vis) {
                $a = $conn->prepare("SELECT a.id, a.title, a.body, a.version, a.is_archived FROM knowledge_articles a WHERE a.id = ?" . $vis[0]);
                $a->execute(array_merge([(int)$r['article_id']], $vis[1]));
                $article = $a->fetch(PDO::FETCH_ASSOC) ?: null;
            }
            $out[] = [
                'id'          => (int)$r['id'],
                'article_id'  => (int)$r['article_id'],
                // A title the viewer may not read is not shown: it can say what the restricted article is about.
                'title'       => $article ? (string)$article['title'] : null,
                'may_read'    => $article !== null,
                'body'        => $article ? (string)$article['body'] : null,
                'version'     => $article ? (int)$article['version'] : null,
                'frozen'      => $r['snapshot_id'] !== null
                                    ? ['version' => $r['article_version'] !== null ? (int)$r['article_version'] : null,
                                       'at_utc' => $r['frozen_datetime'], 'snapshot_id' => (int)$r['snapshot_id']]
                                    : null,
            ];
        }
        return $out;
    }

    /** Articles this analyst may read whose title matches, for the picker. */
    public static function search(PDO $conn, int $viewerId, string $q): array
    {
        if (!self::mayReadKnowledge($conn, $viewerId)) return [];
        [$visSql, $visArgs] = self::visibility($conn, $viewerId);
        $st = $conn->prepare("SELECT a.id, a.title FROM knowledge_articles a WHERE a.title LIKE ?" . $visSql . " ORDER BY a.title LIMIT 20");
        $st->execute(array_merge(['%' . $q . '%'], $visArgs));
        return array_map(fn($r) => ['id' => (int)$r['id'], 'title' => (string)$r['title']], $st->fetchAll(PDO::FETCH_ASSOC));
    }

    /** Reference an article from a task. Only one the adder may read, so nobody attaches what they cannot see. */
    public static function add(PDO $conn, ActorContext $ctx, int $taskId, int $articleId): int
    {
        self::guardWrite($conn, $ctx, $taskId);
        $vis = self::mayReadKnowledge($conn, $ctx->actorId) ? self::visibility($conn, $ctx->actorId) : null;
        if (!$vis) throw new ServiceError('forbidden', 'knowledge_access_needed', 'You need access to Knowledge to add a procedure.');
        $a = $conn->prepare("SELECT a.id, a.title FROM knowledge_articles a WHERE a.id = ?" . $vis[0]);
        $a->execute(array_merge([$articleId], $vis[1]));
        $art = $a->fetch(PDO::FETCH_ASSOC);
        if (!$art) throw new ServiceError('not_found', 'not_found', 'Article not found.');
        $dup = $conn->prepare("SELECT 1 FROM task_procedures WHERE task_id = ? AND article_id = ?");
        $dup->execute([$taskId, $articleId]);
        if ($dup->fetchColumn()) throw new ServiceError('conflict', 'conflict', 'That procedure is already on this task.');
        $sort = $conn->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM task_procedures WHERE task_id = ?");
        $sort->execute([$taskId]);
        $conn->prepare("INSERT INTO task_procedures (task_id, article_id, title, sort_order, added_by_id, added_datetime) VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP())")
             ->execute([$taskId, $articleId, (string)$art['title'], (int)$sort->fetchColumn(), $ctx->actorId ?: null]);
        $id = (int)$conn->lastInsertId();
        TaskHistory::record($conn, $taskId, 'procedure_added', $ctx, null, (string)$art['title'], ['article_id' => $articleId]);
        return $id;
    }

    /** Take a procedure off a task. Its frozen text, if any, stays in the snapshots. */
    public static function remove(PDO $conn, ActorContext $ctx, int $procedureId): int
    {
        $st = $conn->prepare("SELECT * FROM task_procedures WHERE id = ?");
        $st->execute([$procedureId]);
        $r = $st->fetch(PDO::FETCH_ASSOC);
        if (!$r) throw new ServiceError('not_found', 'not_found', 'That procedure is no longer on this task.');
        self::guardWrite($conn, $ctx, (int)$r['task_id']);
        $conn->prepare("DELETE FROM task_procedures WHERE id = ?")->execute([$procedureId]);
        TaskHistory::record($conn, (int)$r['task_id'], 'procedure_removed', $ctx, (string)$r['title'], null, ['article_id' => (int)$r['article_id']]);
        return (int)$r['task_id'];
    }

    /** The task a procedure reference belongs to, or 0. */
    public static function taskIdFor(PDO $conn, int $procedureId): int
    {
        $st = $conn->prepare("SELECT task_id FROM task_procedures WHERE id = ?");
        $st->execute([$procedureId]);
        return (int)$st->fetchColumn();
    }

    /**
     * Freeze each referenced article's text as it stands now - called by the status hook when
     * an occurrence is completed or skipped. Reads the article directly (not through a viewer):
     * this is the record of what the work was done against, not something being shown.
     * An article since deleted keeps whatever was frozen before. Never throws.
     */
    public static function freeze(PDO $conn, int $taskId, bool $strict = false): void
    {
        if (!self::ready($conn)) return;
        try {
            $st = $conn->prepare("SELECT p.id, p.article_id, a.title, a.body, a.version
                                    FROM task_procedures p JOIN knowledge_articles a ON a.id = p.article_id
                                   WHERE p.task_id = ?");
            $st->execute([$taskId]);
            foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) {
                $hash = hash('sha256', $r['article_id'] . "\0" . $r['title'] . "\0" . (string)$r['body']);
                $conn->prepare("INSERT IGNORE INTO task_procedure_snapshots (content_hash, article_id, title, body, created_datetime)
                                VALUES (?, ?, ?, ?, UTC_TIMESTAMP())")
                     ->execute([$hash, (int)$r['article_id'], (string)$r['title'], (string)$r['body']]);
                $sid = $conn->prepare("SELECT id FROM task_procedure_snapshots WHERE content_hash = ?");
                $sid->execute([$hash]);
                $conn->prepare("UPDATE task_procedures SET snapshot_id = ?, article_version = ?, frozen_datetime = UTC_TIMESTAMP() WHERE id = ?")
                     ->execute([(int)$sid->fetchColumn(), (int)$r['version'], (int)$r['id']]);
            }
        } catch (Throwable $e) {
            // Strict (inside the completion's transaction): no completion without its procedure record.
            if ($strict) throw TaskHistory::notRecorded('procedure', $e);
            error_log('[task procedures] freeze failed for task ' . $taskId . ': ' . $e->getMessage());
        }
    }

    /** The repeat's copy: the next occurrence follows the same procedures (unfrozen). */
    public static function copyForOccurrence(PDO $conn, int $fromTaskId, int $toTaskId): void
    {
        if (!self::enabled($conn) || !self::ready($conn)) return;
        try {
            $conn->prepare("INSERT INTO task_procedures (task_id, article_id, title, sort_order, added_datetime)
                            SELECT ?, article_id, title, sort_order, UTC_TIMESTAMP() FROM task_procedures WHERE task_id = ?")
                 ->execute([$toTaskId, $fromTaskId]);
        } catch (Throwable $e) {
            error_log('[task procedures] copy to occurrence ' . $toTaskId . ' failed: ' . $e->getMessage());
        }
    }

    /** Remove the references of deleted tasks. The frozen texts stay - a removed occurrence's record points at them. */
    public static function deleteForTasks(PDO $conn, array $taskIds): void
    {
        if (!$taskIds) return;
        try {
            $place = implode(',', array_fill(0, count($taskIds), '?'));
            $conn->prepare("DELETE FROM task_procedures WHERE task_id IN ($place)")->execute(array_values($taskIds));
        } catch (Throwable) { /* before Database Verification */ }
    }

    /** For the #181 frozen record: each reference and what it was frozen against. */
    public static function snapshot(PDO $conn, int $taskId, bool $strict = false): array
    {
        if (!self::ready($conn)) return [];
        try {
            $st = $conn->prepare("SELECT article_id, title, snapshot_id, article_version, frozen_datetime FROM task_procedures WHERE task_id = ? ORDER BY sort_order, id");
            $st->execute([$taskId]);
            return $st->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            if ($strict) throw $e;
            return [];
        }
    }

    /** Frozen texts by id, for the evidence report's "Procedures used" section. */
    public static function snapshots(PDO $conn, array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if (!$ids || !self::ready($conn)) return [];
        $place = implode(',', array_fill(0, count($ids), '?'));
        $st = $conn->prepare("SELECT id, content_hash, article_id, title, body, created_datetime FROM task_procedure_snapshots WHERE id IN ($place)");
        $st->execute($ids);
        $out = [];
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $out[(int)$r['id']] = $r;
        return $out;
    }

    /** Tasks write, the task visible, and the #181 completed-checklist lock. */
    private static function guardWrite(PDO $conn, ActorContext $ctx, int $taskId): void
    {
        require_once __DIR__ . '/tasks.php';
        require_once __DIR__ . '/checklists.php';
        TasksService::assertCanWrite($conn, $ctx);
        if ($ctx->actorId > 0 && function_exists('analystCanAccessTask') && !analystCanAccessTask($conn, $ctx->actorId, $taskId)) {
            throw new ServiceError('not_found', 'not_found', 'Task not found.');
        }
        ChecklistsService::assertTaskChecklistChangeAllowed($conn, $taskId);
    }
}
