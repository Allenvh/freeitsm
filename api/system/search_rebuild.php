<?php
/**
 * API: rebuild the search index, one slice at a time (discussion #53).
 *
 * ⚠️ WHY IT IS CHUNKED
 * The rebuild is the one operation here that scales with the size of the
 * installation. On a small one it takes under a second; on years of tickets it
 * would run past max_execution_time and die halfway, leaving a partly-rebuilt
 * index and no way to tell how far it got. So the client calls this repeatedly:
 * each call indexes a slice of tickets and reports where it stopped, and the
 * caller passes that back as `since_ticket_id`.
 *
 * That also gives an honest progress bar for free, rather than a spinner that
 * cannot say whether anything is happening.
 *
 * Knowledge articles are a separate pass and are done on the LAST slice only —
 * they are not keyed by ticket id, so including them in every slice would redo
 * all of them every time.
 *
 * Rebuilding is always SAFE: every write is an upsert keyed on
 * (source_type, source_id), so a slice that runs twice updates in place.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/search/backfill.php';
require_once '../../includes/admin_api_guard.php';   // administrators only

header('Content-Type: application/json');

// Tickets per call. Small enough that one slice cannot time out on a slow box,
// large enough that a big install does not need hundreds of round trips.
const SEARCH_REBUILD_SLICE = 200;

try {
    $in    = json_decode(file_get_contents('php://input'), true) ?: [];
    $since = max(0, (int)($in['since_ticket_id'] ?? 0));

    $conn = connectToDatabase();

    // Do the ticket slice first, without articles.
    $res = searchBackfillRun($conn, [
        'since_ticket_id' => $since,
        'limit'           => SEARCH_REBUILD_SLICE,
        'articles'        => false,
    ]);

    $ticketsDone = $res['tickets_remaining'] === 0;

    // Articles ride on the final slice, once no tickets are left.
    $articles = 0;
    if ($ticketsDone) {
        $final = searchBackfillRun($conn, [
            // A ticket id beyond the end: this pass is only here for the articles,
            // and re-walking the tickets would double the work of the last slice.
            'since_ticket_id' => PHP_INT_MAX,
            'articles'        => true,
        ]);
        $articles = $final['articles'];

        // Tidy up rows belonging to tickets that have since been trashed. Only on
        // the last slice — it is a single sweep, not per-slice work.
        searchBackfillPrune($conn);
    }

    echo json_encode([
        'success'           => true,
        'done'              => $ticketsDone,
        'last_ticket_id'    => $res['last_ticket_id'],
        'tickets_remaining' => $res['tickets_remaining'],
        'indexed'           => [
            'tickets'  => $res['tickets'],
            'emails'   => $res['emails'],
            'notes'    => $res['notes'],
            'articles' => $articles,
        ],
        'seconds' => $res['seconds'],
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
