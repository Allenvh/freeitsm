<?php
/**
 * API: create or update a morning check group (discussion #64).
 *
 * ⚠️ A group's assignment is routing, not permission. Saving one here changes
 * who a check is LABELLED for and how the dashboard filters — it does not and
 * must not change who may complete it. See MorningChecksService.
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
requireCapabilityJson(Cap::MORNING_CHECKS_GROUPS);   // settings tab — see docs/design/rbac.md

try {
    $conn = connectToDatabase();
    $in   = json_decode(file_get_contents('php://input'), true) ?: [];
    $id   = MorningChecksService::saveGroup($conn, ActorContext::fromSession($conn), $in);
    echo json_encode(['success' => true, 'id' => $id]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
