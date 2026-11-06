<?php
/**
 * API: delete a morning check group (discussion #64).
 *
 * ⚠️ The group goes; its CHECKS DO NOT. They fall back to ungrouped and carry on
 * appearing in the round. Deleting a way of organising work should never delete
 * the work, and "delete group" is precisely the button somebody presses
 * expecting only the grouping to disappear. The response says how many checks
 * were freed so the UI can tell them.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/morning_checks.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('morning-checks');
requireCapabilityJson(Cap::MORNING_CHECKS_GROUPS);

try {
    $conn = connectToDatabase();
    $in   = json_decode(file_get_contents('php://input'), true) ?: [];
    $id   = (int)($in['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'error' => 'No group.']);
        exit;
    }
    $freed = MorningChecksService::deleteGroup($conn, ActorContext::fromSession($conn), $id);
    echo json_encode(['success' => true, 'checks_ungrouped' => $freed]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
