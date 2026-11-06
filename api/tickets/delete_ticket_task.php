<?php
/**
 * API: Unlink a task from a ticket (discussion #83).
 *
 * ⚠️ Unlinks, never deletes. The link is `tasks.ticket_id`, so removing it just
 * clears that column and the task carries on living in the Tasks module. The ✕
 * on a pill in the ticket's Links strip means "this is not related to this
 * ticket", not "throw this work away" — deleting is done from the task itself,
 * where the consequences are visible.
 *
 * The task keeps its company. It was stamped when the task was created and does
 * not follow the link around; a task that stops belonging to a ticket does not
 * thereby stop belonging to the client.
 *
 * POST { "ticket_id": N, "task_id": N }
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tasks');

try {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $ticketId = (int)($input['ticket_id'] ?? 0);
    $taskId   = (int)($input['task_id'] ?? 0);

    if ($ticketId <= 0 || $taskId <= 0) throw new Exception('ticket_id and task_id are required');

    $conn      = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
        throw new Exception('Ticket not found');
    }
    if (!analystCanAccessTask($conn, $analystId, $taskId)) {
        throw new Exception('Task not found');
    }

    // Scoped to this ticket: unlinking is only ever "detach from the ticket I am
    // looking at", so a stale pill cannot clear a link made somewhere else.
    $stmt = $conn->prepare("UPDATE tasks SET ticket_id = NULL, updated_datetime = UTC_TIMESTAMP() WHERE id = ? AND ticket_id = ?");
    $stmt->execute([$taskId, $ticketId]);

    echo json_encode(['success' => true, 'unlinked' => $stmt->rowCount() > 0]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
