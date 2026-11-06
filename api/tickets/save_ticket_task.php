<?php
/**
 * API: Attach a task to a ticket — either an existing one, or a new one created
 * from what the analyst typed (discussion #83).
 *
 * One endpoint for both because the reading pane offers one control for both.
 * Typing in the picker searches existing tasks and offers to create from the
 * same box, so "create" and "link" are the same gesture and you cannot make a
 * duplicate of a task that is already sitting there in the results.
 *
 * POST { "ticket_id": N, "task_id": N }    → link an existing task
 * POST { "ticket_id": N, "title": "..." }  → create a task against the ticket
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/tasks.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
// Reading a task pill is part of the ticket; making one is doing Tasks work.
requireModuleAccessJson('tasks');

try {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $ticketId = (int)($input['ticket_id'] ?? 0);
    $taskId   = (int)($input['task_id'] ?? 0);
    $title    = trim((string)($input['title'] ?? ''));

    if ($ticketId <= 0) throw new Exception('ticket_id is required');

    $conn      = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
        throw new Exception('Ticket not found');
    }

    // ---- Create a new task against this ticket -------------------------------
    if ($taskId <= 0) {
        if ($title === '') throw new Exception('Either task_id or title is required');

        // Through the service, so the task gets the ticket's company, the default
        // status and priority, and the task.created workflow event — the same as
        // a task made in the Tasks module. Deliberately NOT copying the ticket's
        // priority or dates: two copies of the truth drift apart, and the link is
        // one click from the ticket anyway.
        //
        // Assigned to whoever made it. This started out unassigned, on the
        // reasoning that handing work to a person should be a deliberate act —
        // but that is the wrong default HERE: raising a task while working a
        // ticket almost always means "I am going to do this", and an unassigned
        // task shows up on nobody's list. Reassigning is one click; noticing a
        // task nobody owns can take days. (Tasks made in the Tasks module keep
        // their own behaviour — this is the ticket path only.)
        $res = TasksService::saveTask($conn, ActorContext::fromSession($conn), [
            'title'               => $title,
            'ticket_id'           => $ticketId,
            'assigned_analyst_id' => $analystId,
        ]);
        echo json_encode(['success' => true, 'task_id' => $res['id'], 'created' => true]);
        exit;
    }

    // ---- Link an existing task ----------------------------------------------
    if (!analystCanAccessTask($conn, $analystId, $taskId)) {
        throw new Exception('Task not found');
    }

    $stmt = $conn->prepare("SELECT tenant_id, parent_task_id FROM tasks WHERE id = ?");
    $stmt->execute([$taskId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$task) throw new Exception('Task not found');

    if ($task['parent_task_id'] !== null) {
        throw new Exception('A subtask belongs to its parent task, so it cannot be linked to a ticket on its own.');
    }

    // ⚠️ Both gates can pass and the link still be wrong: an analyst with access
    // to BOTH companies would otherwise be able to attach one company's task to
    // another company's ticket, quietly creating exactly the straddle the scoping
    // work exists to prevent. The CMDB module draws the same line — the invariant
    // binds all-access staff too, because a scope check alone does not express it.
    $ts = $conn->prepare("SELECT tenant_id FROM tickets WHERE id = ?");
    $ts->execute([$ticketId]);
    $ticketTenant = $ts->fetchColumn();

    if (isMultiTenant($conn)) {
        $default = getDefaultTenantId($conn);
        $taskCo   = $task['tenant_id']   === null ? $default : (int)$task['tenant_id'];
        $ticketCo = ($ticketTenant === false || $ticketTenant === null) ? $default : (int)$ticketTenant;
        if ($taskCo !== $ticketCo) {
            throw new Exception('That task belongs to a different company from this ticket.');
        }
    }

    $conn->prepare("UPDATE tasks SET ticket_id = ?, updated_datetime = UTC_TIMESTAMP() WHERE id = ?")
         ->execute([$ticketId, $taskId]);

    echo json_encode(['success' => true, 'task_id' => $taskId, 'created' => false]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
