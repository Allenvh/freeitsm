<?php
/**
 * API Endpoint: one page of the ticket table (PR #213, by @Dr4inder)
 *
 * GET: page, page_size (25/50/100), sort, dir, q, filters (JSON), view
 * (active/snoozed/trash), mine, include_closed, search_content. Returns the page's
 * rows, the total, and the counts for each column filter.
 *
 * The rows come from ticketListBase() in includes/ticket_list.php - the message
 * list's own query - so the table shows exactly the tickets the list would.
 * Called by assets/js/tickets-table.js through the shared data table's server mode.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/ticket_list.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');

try {
    $query = ticketListQuery($_GET);
    $page  = ticketListRead(connectToDatabase(), (int)$_SESSION['analyst_id'], $query);
    echo json_encode(['success' => true] + $page);
} catch (InvalidArgumentException | JsonException $e) {
    // A request the page did not build: say what was wrong with it.
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    // Anything else is ours. The table keeps its last rows and offers Retry.
    http_response_code(503);
    echo json_encode(['success' => false, 'error' => 'Ticket list unavailable']);
}
