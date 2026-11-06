<?php
/**
 * API Endpoint: an administrator approves a change without the vote, with a written
 * reason (#180 Part 1 §8, Administrator override).
 *
 *   POST { change_id, reason }
 *
 * Off unless Settings → Approvals turns it on. Thin adapter over
 * ChangesService::overrideApproval, which checks the setting, that the actor is an
 * administrator, and that a reason was given, and records the round as 'override'.
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

try {
    $conn = connectToDatabase();
    ChangesService::overrideApproval($conn, ActorContext::fromSession($conn), $changeId, ['reason' => $input['reason'] ?? '']);
    echo json_encode(['success' => true]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
