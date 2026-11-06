<?php
/**
 * API Endpoint: Log time against a task (GH #112).
 * Thin UI adapter over TasksService::createTimeEntry().
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/tasks.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tasks');
requireModuleWriteJson('tasks');   // Read only access to Tasks writes nothing (#181 Part G)

try {
    $data   = json_decode(file_get_contents('php://input'), true) ?: [];
    $taskId = isset($data['task_id']) ? (int)$data['task_id'] : 0;
    if ($taskId <= 0) {
        throw new Exception('Task ID is required');
    }
    $conn = connectToDatabase();
    $ctx  = ActorContext::fromSession($conn);
    $id   = TasksService::createTimeEntry($conn, $ctx, $taskId, [
        'minutes'  => $data['time_spent_minutes'] ?? 0,
        'notes'    => $data['notes'] ?? '',
        'entry_at' => !empty($data['entry_datetime']) ? $data['entry_datetime'] : null,
    ]);
    echo json_encode(['success' => true, 'id' => $id]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
