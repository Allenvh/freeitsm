<?php
/**
 * API: Tasks — move a task to another company (tenant).
 *
 * Thin UI adapter over TasksService::moveTaskToCompany, which holds every rule
 * this has: both-ends access, the subtask and linked-record refusals, and
 * moving the whole subtree in one transaction. Nothing is decided here, so the
 * REST API and the board cannot end up disagreeing about what a move means.
 *
 * The Tasks twin of api/change-management/move_to_company.php. No-op surface on
 * a single-company install — the button is not rendered, and the service
 * refuses anyway if something calls this directly.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/tasks.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tasks');
requireModuleWriteJson('tasks');   // Read only access to Tasks writes nothing (#181 Part G)

$input    = json_decode(file_get_contents('php://input'), true) ?: [];
$taskId   = (int)($input['task_id'] ?? $input['id'] ?? 0);
$targetId = (int)($input['tenant_id'] ?? 0);

if ($taskId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Task ID is required']);
    exit;
}
if ($targetId <= 0) {
    echo json_encode(['success' => false, 'error' => 'A target company is required']);
    exit;
}

try {
    $conn = connectToDatabase();
    $res = TasksService::moveTaskToCompany($conn, ActorContext::fromSession($conn), $taskId, $targetId);

    // `moved` counts the task PLUS its subtasks, so the message can say so
    // rather than letting somebody discover it from the board afterwards.
    echo json_encode([
        'success'      => true,
        'moved'        => $res['moved'],
        'company_name' => $res['to'],
        'message'      => $res['moved'] === 0
            ? 'Task is already in that company.'
            : ($res['moved'] === 1
                ? 'Task moved to ' . $res['to']
                : 'Task and ' . ($res['moved'] - 1) . ' subtask(s) moved to ' . $res['to']),
    ]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
