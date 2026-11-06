<?php
/**
 * API Endpoint: search INSIDE tickets — message bodies, notes and subjects.
 *
 * The sibling of search_tickets.php, which matches only a ticket number, an
 * address or a subject. This one asks the search corpus, so it finds a phrase
 * buried in the fourth reply of a two-year-old ticket.
 *
 * ⚠️ IT DOES NOT DECIDE PERMISSIONS ITSELF. It builds a scope from the analyst's
 * session and hands it to the one search function, which applies it INSIDE the
 * query. Filtering results here would starve them — the index returns its top N
 * by relevance, and discarding afterwards hands back a near-empty page while
 * plenty the analyst was entitled to never made the cut.
 */

session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/search/search.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');

$input = json_decode(file_get_contents('php://input'), true);
$query = trim((string)($input['query'] ?? ''));
$limit = max(1, min(50, (int)($input['limit'] ?? 25)));

if ($query === '') {
    echo json_encode(['success' => false, 'error' => 'Enter something to search for']);
    exit;
}

try {
    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    // The scope is a data structure. Company scoping mirrors ticketTenantFilter();
    // internal notes are included because this is the analyst-facing search.
    //
    // require_ticket keeps the count and the list telling the same story. This
    // endpoint renders ticket rows only, so anything in the corpus that is not
    // attached to a ticket — a knowledge article, say — was being counted in the
    // total and then dropped when the rows were built, producing "4 tickets found"
    // above three of them. It also stopped those documents consuming result slots
    // that a matching ticket could have had.
    $scope = searchScopeForAnalyst($conn, $analystId, [
        'include_internal' => true,
        'require_ticket'   => true,
        // "Include closed tickets" (#149) - on unless the panel switched it off.
        'exclude_closed'   => array_key_exists('include_closed', $input ?? []) && !$input['include_closed'],
    ]);

    $res = searchCorpusQuery($conn, $query, $scope, ['limit' => $limit]);

    if (!$res['ok']) {
        // Distinguish "we couldn't search" from "nothing matched" — a person who
        // typed only short words deserves to be told, not shown an empty page
        // that reads as "it isn't in your tickets".
        echo json_encode([
            'success'    => true,
            'results'    => [],
            'total'      => 0,
            'reason'     => $res['reason'],
            'dropped'    => $res['query']['dropped'] ?? [],
            'min_length' => searchMinTokenSize($conn),
        ]);
        exit;
    }

    // Decorate each ticket with what the inbox needs to open it. Reading these
    // from `tickets`/`emails` rather than the corpus keeps the corpus a search
    // index rather than a second copy of the ticket list.
    $out = [];
    $ids = array_values(array_filter(array_map(fn($g) => $g['ticket_id'], $res['results'])));
    $meta = [];
    if ($ids) {
        $in = implode(',', array_fill(0, count($ids), '?'));
        $st = $conn->prepare(
            "SELECT t.id, t.ticket_number, t.subject, ts.name AS status,
                    (SELECT e.id FROM emails e WHERE e.ticket_id = t.id ORDER BY e.is_initial DESC, e.id ASC LIMIT 1) AS email_id
               FROM tickets t
               LEFT JOIN ticket_statuses ts ON ts.id = t.status_id
              WHERE t.id IN ($in)"
        );
        $st->execute($ids);
        foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $r) $meta[(int)$r['id']] = $r;
    }

    foreach ($res['results'] as $g) {
        $tid = $g['ticket_id'];
        if ($tid === null || !isset($meta[$tid])) continue;   // corpus row whose ticket has gone
        $m = $meta[$tid];
        $top = $g['hits'][0] ?? [];
        $out[] = [
            'ticket_id'     => $tid,
            'email_id'      => $m['email_id'] !== null ? (int)$m['email_id'] : null,
            'ticket_number' => $m['ticket_number'],
            'subject'       => $m['subject'],
            'status'        => $m['status'],
            'matched'       => $g['matched'],
            'snippet'       => (string)($top['snippet'] ?? ''),
            'hit_count'     => count($g['hits']),
        ];
    }

    echo json_encode([
        'success' => true,
        'results' => $out,
        'total'   => $res['total'],
        'dropped' => $res['query']['dropped'] ?? [],
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Search failed']);
}
