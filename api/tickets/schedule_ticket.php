<?php
/**
 * API Endpoint: Schedule work for a ticket (set/clear work_start_datetime).
 * Thin UI adapter over TicketsService::updateTicket (writeAudit=false).
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/tickets.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tickets');

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['ticket_id'])) { echo json_encode(['success' => false, 'error' => 'Ticket ID required']); exit; }

$ticketId = (int)$input['ticket_id'];
$workStart = $input['work_start_datetime'] ?? null;

// The end and the all-day flag are OPTIONAL on the wire. The modal always sends
// them, but a caller that predates them (or a cached copy of inbox.js still in
// somebody's browser after a deploy) must keep working: omitting them leaves the
// stored values alone rather than silently clearing a ticket's duration.
$fields = ['work_start_at' => $workStart];
if (array_key_exists('work_end_datetime', $input)) {
    $fields['work_end_at'] = $input['work_end_datetime'];
}
if (array_key_exists('all_day', $input)) {
    $fields['work_all_day'] = $input['all_day'];
}

try {
    $conn = connectToDatabase();
    TicketsService::updateTicket($conn, ActorContext::fromSession($conn), $ticketId, $fields, false);
    echo json_encode(['success' => true, 'message' => $workStart ? 'Work scheduled' : 'Schedule cleared']);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
