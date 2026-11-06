<?php
/**
 * API Endpoint: save the approval rules (Change Management → Settings → Approvals, #180).
 *
 * Behind Cap::CHANGES_APPROVALS, marked sensitive. Every value is checked against the
 * choices the tab offers - nothing typed is stored as a rule. Switching to Enforce is
 * refused while anything stands in its way (an active status with no stage), with the
 * reason, because Enforce built on an unclassified status would switch itself off.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/changes/approvals.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('changes');
requireCapabilityJson(Cap::CHANGES_APPROVALS);   // settings tab - see docs/design/rbac.md

try {
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $conn = connectToDatabase();

    $pick = function (string $key, array $allowed) use ($in) {
        $v = (string)($in[$key] ?? '');
        if (!in_array($v, $allowed, true)) {
            throw new Exception("Unknown value for {$key}");
        }
        return $v;
    };
    $list = function (string $key, array $allowed) use ($in) {
        $v = $in[$key] ?? [];
        if (!is_array($v)) throw new Exception("{$key} must be a list");
        $bad = array_diff($v, $allowed);
        if ($bad) throw new Exception("Unknown value for {$key}");
        return implode(',', array_values(array_unique($v)));
    };

    $values = [
        'change_approval_mode'             => $pick('mode', ['record', 'enforce']),
        'change_approval_own_allowed'      => !empty($in['own_allowed']) ? '1' : '0',
        'change_approval_own_people'       => $list('own_people', array_keys(CHANGE_APPROVAL_OWN_CHOICES)),
        'change_approval_override'         => !empty($in['override']) ? '1' : '0',
        'change_approval_material_fields'  => $list('material', array_keys(CHANGE_MATERIAL_CHOICES)),
        'change_approval_choose_approvers' => $pick('choose_approvers', ['anyone', 'permission']),
        'change_approval_edit_after'       => $pick('edit_after', ['keep', 'withdraw']),
        'change_approval_stale_vote'       => $pick('stale_vote', ['record', 'refuse']),
        'change_approval_delete_submitted' => $pick('delete_submitted', ['allowed', 'refuse']),
        'change_templates_enabled'         => !empty($in['templates_enabled']) ? '1' : '0',
    ];

    // Where a rejection goes: blank (the first Draft-stage status) or an active status in
    // the Draft or Stopped stage.
    $reject = (string)($in['reject_status_id'] ?? '');
    if ($reject !== '') {
        $s = $conn->prepare("SELECT id FROM change_statuses WHERE id = ? AND is_active = 1 AND stage IN ('draft', 'stopped')");
        $s->execute([(int)$reject]);
        if (!$s->fetchColumn()) {
            throw new Exception(t('change-management.approval_settings.reject_status_invalid'));
        }
    }
    $values['change_approval_reject_status_id'] = $reject === '' ? '' : (string)(int)$reject;

    // Where Cancel goes: blank (the last Stopped-stage status) or an active Stopped one.
    $cancel = (string)($in['cancel_status_id'] ?? '');
    if ($cancel !== '') {
        $s = $conn->prepare("SELECT id FROM change_statuses WHERE id = ? AND is_active = 1 AND stage = 'stopped'");
        $s->execute([(int)$cancel]);
        if (!$s->fetchColumn()) {
            throw new Exception(t('change-management.approval_settings.cancel_status_invalid'));
        }
    }
    $values['change_approval_cancel_status_id'] = $cancel === '' ? '' : (string)(int)$cancel;

    if ($values['change_approval_mode'] === 'enforce') {
        $blockers = changeApprovalEnforceBlockers($conn);
        if ($blockers) {
            throw new Exception(t('change-management.approval_settings.enforce_blocked', ['statuses' => implode(', ', $blockers)]));
        }
    }

    $conn->beginTransaction();
    $del = $conn->prepare("DELETE FROM system_settings WHERE setting_key = ?");
    $ins = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value, updated_datetime) VALUES (?, ?, UTC_TIMESTAMP())");
    foreach ($values as $k => $v) {
        $del->execute([$k]);
        $ins->execute([$k, $v]);
    }
    $conn->commit();

    echo json_encode(['success' => true, 'settings' => changeApprovalSettings($conn, true)]);
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
