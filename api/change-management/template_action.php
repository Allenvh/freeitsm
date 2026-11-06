<?php
/**
 * API Endpoint: approve a submitted template version, or retire a template (#180 §8).
 *
 *   POST { action: 'approve', version_id, note }
 *        - needs "Approve standard-change templates"; the version's author cannot approve
 *          it (administrators included), unless Settings allows approving your own
 *   POST { action: 'retire', template_id }
 *        - needs "Write standard-change templates"; no new change may use it, and the
 *          changes it already approved keep their approval
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/changes/templates.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('changes');

try {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $conn = connectToDatabase();
    $me = (int)$_SESSION['analyst_id'];
    $action = (string)($in['action'] ?? '');
    $conn->beginTransaction();
    if ($action === 'approve') {
        requireActionJson(Cap::CHANGES_TEMPLATES_APPROVE, $conn);
        ChangeTemplates::approveVersion($conn, (int)($in['version_id'] ?? 0), $me, (string)($in['note'] ?? ''));
    } elseif ($action === 'retire') {
        requireModuleWriteJson('changes', $conn);
        requireCapabilityJson(Cap::CHANGES_TEMPLATES, $conn);
        ChangeTemplates::retire($conn, (int)($in['template_id'] ?? 0));
    } else {
        throw new Exception('Unknown action');
    }
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (ServiceError $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
