<?php
/**
 * API Endpoint: delete a checklist category or suggested role.
 *
 * Thin UI adapter over ChecklistsService::deleteLookup.
 *
 * POST { kind: "category"|"role", id: int }
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/checklists.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('checklists');

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;

try {
    $conn = connectToDatabase();
    ChecklistsService::deleteLookup(
        $conn,
        ActorContext::fromSession($conn),
        (string)($data['kind'] ?? ''),
        (int)($data['id'] ?? 0)
    );
    echo json_encode(['success' => true]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
