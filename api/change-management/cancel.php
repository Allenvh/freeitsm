<?php
/**
 * API Endpoint: cancel a change - move it to the first Stopped-stage status, withdraw
 * any open approval round, keep every record (#180 §4).
 *
 *   POST { change_id, reason }
 *
 * What a change with approval history gets instead of Delete, where the settings (and
 * always Enforce mode) refuse to delete it. Thin adapter over ChangesService::cancelChange.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/changes.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('changes');
requireModuleWriteJson('changes');   // Read only access (#180) changes nothing here

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$changeId = (int)($input['change_id'] ?? 0);
if (!$changeId) {
    echo json_encode(['success' => false, 'error' => 'Change ID required']);
    exit;
}

try {
    $conn = connectToDatabase();
    ChangesService::cancelChange($conn, ActorContext::fromSession($conn), $changeId, ['reason' => $input['reason'] ?? '']);
    echo json_encode(['success' => true]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
