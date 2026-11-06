<?php
/**
 * Rebuild the search corpus from ticket content already in the database.
 *
 * Reads `tickets.subject`, `emails.body_content`, `ticket_notes.note_text` and
 * `knowledge_articles.body` — all already text — and, since #53's tier 1, the
 * text of attachments it can read without help (`.txt`, `.docx`, `.xlsx` and
 * friends; see includes/search/extract.php).
 *
 * ⚠️ It does NOT re-open files it has read before. Extraction is cached in
 * `attachment_text`, so a rebuild of the search index costs no file reads at
 * all — which is the whole reason that table exists, and matters far more once
 * tier 2 is doing OCR on PDFs.
 *
 * It is also not a one-off. The same code is the "rebuild the index" command a
 * grown installation needs after a schema change, after the full-text settings
 * are corrected, or simply when someone suspects the corpus has drifted.
 *
 * SAFE TO RE-RUN. Every write is an upsert keyed on (source_type, source_id), so
 * running it twice updates in place rather than duplicating. It only ever writes
 * to `search_documents` — the source tables are read and never modified.
 *
 * ⚠️ COMMITS AS IT GOES, in batches. It cannot wrap the whole run in one
 * transaction: InnoDB does not expose uncommitted rows to MATCH...AGAINST, so
 * nothing indexed would be searchable until the very end, and a long run would
 * hold a huge transaction open for no benefit.
 */

require_once __DIR__ . '/corpus.php';
require_once __DIR__ . '/indexer.php';   // the shared per-ticket document builder

/**
 * Fills the search corpus from existing tickets, emails, notes, attachments and Knowledge
 * articles, in resumable batches.
 *
 * @param array $opts  batch (50-2000), max_body_chars, since_ticket_id (resume point), limit (0 = all), articles (false skips)
 * @param callable|null $progress fn(string $stage, int $done, int $total): void
 * @return array{tickets:int,emails:int,notes:int,articles:int,skipped:int,seconds:float}
 */
function searchBackfillRun(PDO $conn, array $opts = [], ?callable $progress = null): array {
    $batch     = max(50, min(2000, (int)($opts['batch'] ?? 500)));
    $maxBody   = max(1000, (int)($opts['max_body_chars'] ?? 200000));
    $sinceId   = (int)($opts['since_ticket_id'] ?? 0);
    $limit     = (int)($opts['limit'] ?? 0);          // 0 = everything; used for a quick sample
    $started   = microtime(true);
    // Articles are a separate pass. A CHUNKED caller (the Search screen rebuilds
    // in slices so a large install cannot hit max_execution_time) turns them off
    // for every slice but the last, or every slice would redo all of them.
    $doArticles = !isset($opts['articles']) || $opts['articles'];

    // last_ticket_id is what makes the run resumable: the caller passes it back
    // as since_ticket_id to continue where this one stopped.
    $counts    = ['tickets' => 0, 'emails' => 0, 'notes' => 0, 'attachments' => 0, 'articles' => 0,
                  'skipped' => 0, 'last_ticket_id' => $sinceId, 'tickets_remaining' => 0];

    if (!searchCorpusReady($conn)) {
        throw new RuntimeException('search_documents does not exist — run Database Verification first.');
    }

    // Deleted tickets are skipped rather than indexed-then-hidden: a trashed
    // ticket's words should not sit in a searchable table at all.
    $where = "t.deleted_datetime IS NULL AND t.id > ?";
    $args  = [$sinceId];
    $limitSql = $limit > 0 ? " LIMIT $limit" : '';

    $cnt = $conn->prepare("SELECT COUNT(*) FROM tickets t WHERE $where");
    $cnt->execute($args);
    $total = (int)$cnt->fetchColumn();

    $sel = $conn->prepare("SELECT t.id, t.subject, t.tenant_id, t.created_datetime
                             FROM tickets t
                            WHERE $where
                            ORDER BY t.id$limitSql");
    $sel->execute($args);
    $tickets = $sel->fetchAll(PDO::FETCH_ASSOC);

    $done = 0;
    $conn->beginTransaction();
    foreach ($tickets as $t) {
        // ONE definition of what a ticket's corpus rows are, shared with the live
        // indexer (includes/search/indexer.php). It used to be written out again
        // here; if the two ever drifted, a search result would depend on whether
        // a ticket happened to be indexed live or by a rebuild, which is close to
        // undebuggable. The subject/message/note construction now lives there.
        $one = searchIndexTicket($conn, (int)$t['id'], $maxBody);
        $counts['tickets'] += $one['tickets'];
        $counts['attachments'] += $one['attachments'];
        $counts['emails']  += $one['emails'];
        $counts['notes']   += $one['notes'];
        $counts['skipped'] += $one['skipped'];
        $counts['last_ticket_id'] = (int)$t['id'];

        if (++$done % $batch === 0) {
            $conn->commit();
            $conn->beginTransaction();
            if ($progress) $progress('tickets', $done, $total);
        }
    }
    $conn->commit();
    if ($progress) $progress('tickets', $done, $total);

    // How many tickets a resuming caller still has to go. Counted after the pass
    // so a chunked rebuild can show honest progress and know when to stop.
    try {
        $rem = $conn->prepare("SELECT COUNT(*) FROM tickets
                                WHERE deleted_datetime IS NULL AND id > ?");
        $rem->execute([$counts['last_ticket_id']]);
        $counts['tickets_remaining'] = (int)$rem->fetchColumn();
    } catch (Exception $e) { $counts['tickets_remaining'] = 0; }

    if (!$doArticles) {
        $counts['seconds'] = round(microtime(true) - $started, 2);
        return $counts;
    }

    // ── Knowledge articles ──────────────────────────────────────────────────
    // A separate pass, not part of the ticket loop, because an article hangs off
    // no ticket. Archived ones are skipped for the same reason trashed tickets
    // are: the command palette has always excluded them, so indexing them would
    // put results in one search that the rest of the product hides.
    try {
        // ⚠️ Every article, on purpose — see searchIndexArticle(). A backfill that
        // indexed only what the person running it can read would silently build a
        // partial index for the whole install.
        $aSel = $conn->query(
            "SELECT id FROM knowledge_articles
              WHERE is_archived = 0 OR is_archived IS NULL
              ORDER BY id"
        );
        $articleIds = array_map('intval', $aSel->fetchAll(PDO::FETCH_COLUMN));

        $aDone = 0;
        $conn->beginTransaction();
        foreach ($articleIds as $aid) {
            if (searchIndexArticle($conn, $aid, $maxBody)) $counts['articles']++;
            if (++$aDone % $batch === 0) {
                $conn->commit();
                $conn->beginTransaction();
                if ($progress) $progress('articles', $aDone, count($articleIds));
            }
        }
        $conn->commit();
        if ($progress) $progress('articles', $aDone, count($articleIds));
    } catch (Exception $e) {
        // An install without the knowledge module, or a part-migrated one, simply
        // contributes no articles. Tickets are already indexed and committed.
        if ($conn->inTransaction()) $conn->commit();
        error_log('[searchBackfillRun] articles: ' . $e->getMessage());
    }

    $counts['seconds'] = round(microtime(true) - $started, 2);
    return $counts;
}

/**
 * Remove every corpus row whose ticket has since been deleted or trashed.
 * The foreign key handles a hard DELETE; this covers the soft-delete case.
 */
function searchBackfillPrune(PDO $conn): int {
    $sql = "DELETE sd FROM search_documents sd
            JOIN tickets t ON t.id = sd.ticket_id
            WHERE t.deleted_datetime IS NOT NULL";
    $st = $conn->prepare($sql);
    $st->execute();
    return $st->rowCount();
}
