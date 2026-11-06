<?php
/**
 * API (POST): Save custom field answers on a ticket (#179) - the ticket panel
 * saves each field as it changes. {ticket_id, values: {field_key: value}};
 * '' clears a field.
 *
 * Goes through TicketsService::updateTicket(), like every other ticket edit, so
 * the company scope, the trash check and the validation are the shared ones.
 * The history rows are written by the service, not by the browser.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/tickets.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tickets');

try {
    $conn = connectToDatabase();
    $ctx  = ActorContext::fromSession($conn);
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $ticketId = (int)($data['ticket_id'] ?? 0);
    $values = $data['values'] ?? null;
    if ($ticketId <= 0 || !is_array($values) || !$values) {
        echo json_encode(['success' => false, 'error' => 'ticket_id and values are required']);
        exit;
    }
    TicketsService::updateTicket($conn, $ctx, $ticketId, ['custom_fields' => $values], false);
    $ticket = TicketsService::loadTicket($conn, $ctx, $ticketId);
    echo json_encode(['success' => true, 'fields' => TicketFieldsService::forTicket($conn, $ticket)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
