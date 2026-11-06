<?php
/**
 * API Endpoint: create or rename a checklist category or suggested role.
 *
 * Thin UI adapter over ChecklistsService::saveLookup — the rules (name required,
 * length cap, uniqueness, and carrying referencing rows across a rename) live in
 * the service so the settings screen and anything added later cannot disagree.
 *
 * POST { kind: "category"|"role", id?: int, name: string }
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/checklists.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('checklists');

$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$kind = (string)($data['kind'] ?? '');

try {
    $conn = connectToDatabase();
    $id = ChecklistsService::saveLookup($conn, ActorContext::fromSession($conn), $kind, $data);
    echo json_encode(['success' => true, 'id' => $id]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
