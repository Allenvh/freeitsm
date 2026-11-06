<?php
/**
 * API Endpoint: the named approver decides a change awaiting their approval (#180).
 *
 *   POST { change_id, decision: 'approve'|'reject', note, approval_id, content_hash }
 *
 * Thin UI adapter over ChangesService::decideApproval, which checks that the actor IS
 * the change's approver, that the round is open, and that approval_id / content_hash
 * are what the page was drawn with (refused as approval_stale where the settings say
 * so). Deciding is identity-based, not a module write: being the named approver is
 * the permission.
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

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$changeId = (int)($input['change_id'] ?? 0);

try {
    $conn = connectToDatabase();
    $res = ChangesService::decideApproval($conn, ActorContext::fromSession($conn), $changeId, [
        'decision'     => $input['decision'] ?? '',
        'note'         => $input['note'] ?? '',
        'approval_id'  => $input['approval_id'] ?? null,
        'content_hash' => $input['content_hash'] ?? null,
    ]);
    echo json_encode(['success' => true] + $res);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
