<?php
/**
 * API Endpoint: Remove a time entry from a task (GH #112).
 * Soft delete, and only the analyst who logged it may do so.
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
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $id   = isset($data['id']) ? (int)$data['id'] : 0;
    if ($id <= 0) {
        throw new Exception('Entry ID is required');
    }
    $conn = connectToDatabase();
    TasksService::deleteTimeEntry($conn, ActorContext::fromSession($conn), $id);
    echo json_encode(['success' => true]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
