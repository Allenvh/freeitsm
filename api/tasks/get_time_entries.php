<?php
/**
 * API Endpoint: Time entries for a task, with totals (GH #112).
 * Thin UI adapter over TasksService::timeEntriesFor().
 *
 * Returns both totals: this task alone, and the roll-up including its subtasks —
 * the latter being the number that answers "how long did this take" once a piece
 * of work has been broken up.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/tasks.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tasks');

try {
    $taskId = isset($_GET['task_id']) ? (int)$_GET['task_id'] : 0;
    if ($taskId <= 0) {
        throw new Exception('Task ID is required');
    }
    $conn = connectToDatabase();
    $ctx  = ActorContext::fromSession($conn);
    $res  = TasksService::timeEntriesFor($conn, $ctx, $taskId);

    // The display rule travels with the data, so the panel does not have to fetch
    // the settings separately and cannot render a form the server would refuse.
    $task = $conn->prepare("SELECT parent_task_id FROM tasks WHERE id = ?");
    $task->execute([$taskId]);
    $parent = $task->fetchColumn();
    $res['allowed'] = TasksService::timeAllowedFor($conn, ($parent === false || $parent === null) ? null : (int)$parent);
    $res['success'] = true;
    echo json_encode($res);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
