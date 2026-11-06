<?php
/**
 * API: wake a sleeping ticket early — the undo half of snooze_ticket.php.
 *
 * POST { ticket_id | ticket_ids[] } -> { success, woken, ticket_ids[] }
 *
 * Reports how many were ACTUALLY asleep, so the caller can say "nothing to wake"
 * rather than claiming to have done something. Waking a ticket that is already
 * awake is a no-op, not an error: two analysts pressing Wake a second apart
 * should both see a sensible outcome.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/ticket_snooze.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tickets');

try {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];

    $ids = [];
    if (isset($data['ticket_ids']) && is_array($data['ticket_ids'])) {
        foreach ($data['ticket_ids'] as $id) { $id = (int)$id; if ($id > 0) $ids[] = $id; }
    } elseif (isset($data['ticket_id'])) {
        $id = (int)$data['ticket_id'];
        if ($id > 0) $ids[] = $id;
    }
    $ids = array_values(array_unique($ids));
    if (!$ids) throw new Exception('ticket_id is required');

    $analystId = (int)$_SESSION['analyst_id'];
    $conn = connectToDatabase();

    $woken = [];
    $denied = 0;
    foreach ($ids as $ticketId) {
        if (!analystCanAccessTicket($conn, $analystId, $ticketId)) { $denied++; continue; }
        if (wakeTicket($conn, $ticketId, $analystId, 'Woken early')) $woken[] = $ticketId;
    }

    if ($denied === count($ids)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Ticket not found']);
        exit;
    }

    echo json_encode(['success' => true, 'woken' => count($woken), 'ticket_ids' => $woken]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
