<?php
/**
 * API: what the search index currently holds (discussion #53).
 *
 * Powers System → Search. Everything here was already visible in
 * D007 — Search corpus health, but D007 is a diagnostic tool: it lives under
 * Debug Tools, prints a wall of environment detail, and is not somewhere an
 * administrator would think to look to answer "is search working?".
 *
 * Read-only. The rebuild is a separate endpoint so that this one can be polled.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/search/corpus.php';
require_once '../../includes/search/extract.php';   // the status constants
require_once '../../includes/search/search.php';
require_once '../../includes/admin_api_guard.php';   // administrators only

header('Content-Type: application/json');

try {
    $conn = connectToDatabase();

    // Opportunistic draining (discussion #53). Somebody looking at the search
    // index is the ideal moment to read a few waiting documents: they are
    // already here, they care about this specific thing, and a second's delay on
    // a page they opened deliberately is a fair trade for an install with no
    // scheduled task. Silent, tiny, and switchable off in
    // Tickets → Settings → Indexing.
    require_once '../../includes/search/extract_queue.php';
    extractQueueDrainOpportunistic($conn);

    // An install that has never run Database Verification has no corpus. That is
    // a normal state with a clear remedy, not an error — say which it is.
    if (!searchCorpusReady($conn)) {
        echo json_encode([
            'success' => true,
            'ready'   => false,
            'reason'  => 'no_table',
        ]);
        exit;
    }

    $rows = $conn->query(
        "SELECT source_type, COUNT(*) AS rows_count, SUM(CHAR_LENGTH(body)) AS chars
           FROM search_documents GROUP BY source_type"
    )->fetchAll(PDO::FETCH_ASSOC);

    $bySource = [];
    $total = 0;
    foreach ($rows as $r) {
        $bySource[] = [
            'source_type' => $r['source_type'],
            'rows'        => (int)$r['rows_count'],
            'chars'       => (int)$r['chars'],
        ];
        $total += (int)$r['rows_count'];
    }

    $lastIndexed = $conn->query("SELECT MAX(indexed_datetime) FROM search_documents")->fetchColumn();
    $ticketsCovered = (int)$conn->query(
        "SELECT COUNT(DISTINCT ticket_id) FROM search_documents WHERE ticket_id IS NOT NULL"
    )->fetchColumn();

    // What SHOULD be there, so the screen can say "42 tickets have never been
    // indexed" rather than leaving an administrator to compare two numbers.
    $ticketsTotal  = (int)$conn->query("SELECT COUNT(*) FROM tickets WHERE deleted_datetime IS NULL")->fetchColumn();
    $articlesTotal = 0;
    try {
        // Install-wide on purpose: this is corpus HEALTH ("is everything indexed?"),
        // an administrator diagnostic about the index rather than a reading surface.
        // Scoping it to one company would make a complete index look incomplete.
        $articlesTotal = (int)$conn->query(
            "SELECT COUNT(*) FROM knowledge_articles WHERE is_archived = 0 OR is_archived IS NULL"
        )->fetchColumn();
    } catch (Exception $e) { /* module absent — stays 0 */ }

    $articlesIndexed = 0;
    foreach ($bySource as $b) {
        if ($b['source_type'] === SEARCH_SOURCE_KB_ARTICLE) $articlesIndexed = $b['rows'];
    }

    // Attachment extraction, by outcome. §3.4 of the design: the status is shown
    // rather than kept internal, because a search that silently finds nothing —
    // when the file was never readable in the first place — is worse than one
    // that admits it could not read the file.
    $attachments = [];
    try {
        $attachments = $conn->query(
            "SELECT status, COUNT(*) AS n FROM attachment_text GROUP BY status"
        )->fetchAll(PDO::FETCH_KEY_PAIR);
        $attachments = array_map('intval', $attachments);
    } catch (Exception $e) { /* table absent on a part-migrated install */ }

    // The attachments that are NOT searchable, named. Counts alone answer "is
    // something wrong" but not "why isn't THIS file coming up", which is the
    // question an analyst actually arrives with. Capped, newest first.
    $problems = [];
    try {
        $st = $conn->prepare(
            "SELECT t.attachment_id, t.status, t.extractor, t.extracted_datetime,
                    a.filename, a.file_size, e.ticket_id, tk.ticket_number
               FROM attachment_text t
               JOIN email_attachments a ON a.id = t.attachment_id
               JOIN emails e            ON e.id = a.email_id
          LEFT JOIN tickets tk          ON tk.id = e.ticket_id
              WHERE t.status NOT IN (?, ?)
                AND (tk.deleted_datetime IS NULL OR tk.id IS NULL)
           ORDER BY t.extracted_datetime DESC
              LIMIT 50"
        );
        $st->execute([ATT_TEXT_EXTRACTED, ATT_TEXT_TRUNCATED]);
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $p) {
            $problems[] = [
                'filename'      => $p['filename'],
                'status'        => $p['status'],
                'extractor'     => $p['extractor'],
                'ticket_id'     => $p['ticket_id'] !== null ? (int)$p['ticket_id'] : null,
                'ticket_number' => $p['ticket_number'],
                'file_size'     => (int)$p['file_size'],
                'when'          => $p['extracted_datetime'],
            ];
        }
    } catch (Exception $e) { /* table absent */ }

    echo json_encode([
        'success'          => true,
        'ready'            => true,
        'attachments'      => $attachments,
        'problem_files'    => $problems,
        'total_rows'       => $total,
        'by_source'        => $bySource,
        'last_indexed'     => $lastIndexed ?: null,
        'tickets_indexed'  => $ticketsCovered,
        'tickets_total'    => $ticketsTotal,
        'articles_indexed' => $articlesIndexed,
        'articles_total'   => $articlesTotal,
        // The server's own minimum word length. It is the single most common
        // cause of "search finds nothing" and is invisible from the application.
        'min_word_length'  => searchMinTokenSize($conn),
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Could not read the search index']);
}
