<?php
/**
 * API Endpoint: the approval rules (Change Management → Settings → Approvals, #180).
 *
 * Behind Cap::CHANGES_APPROVALS - the rules decide what an approval record proves, so
 * reading them is part of administering them. Returns the stored values (defaults where
 * nothing is stored), the choices the tab offers, and what stands in the way of Enforce.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/changes/approvals.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('changes');
requireCapabilityJson(Cap::CHANGES_APPROVALS);   // settings tab - see docs/design/rbac.md

try {
    $conn = connectToDatabase();
    $cfg = changeApprovalSettings($conn, true);
    $statuses = $conn->query(
        "SELECT id, name, stage, is_active FROM change_statuses ORDER BY display_order, name"
    )->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success'          => true,
        'settings'         => $cfg,
        'material_choices' => array_keys(CHANGE_MATERIAL_CHOICES),
        'own_choices'      => array_keys(CHANGE_APPROVAL_OWN_CHOICES),
        'statuses'         => $statuses,
        'enforce_blockers' => changeApprovalEnforceBlockers($conn),
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
