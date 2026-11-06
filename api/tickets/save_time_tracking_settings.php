<?php
/**
 * API Endpoint: save the time-tracking switches (discussion #72).
 *
 * POST {
 *   default:   { ui: bool, api: bool },
 *   companies: [ { id: N, ui: bool|null, api: bool|null } ]
 * }
 *
 * ⚠️ `null` for a company means "follow the install default" and DELETES its
 * override — it is not the same as false. Without that a company could be given
 * an answer and never handed back to the default again.
 *
 * ⚠️ NOTHING IS DELETED BY TURNING THESE OFF. dschipfel asked for existing data
 * to be preserved and it is: time entries stay exactly where they are and come
 * back untouched when it is switched on again. These switches decide what is
 * SHOWN and what is SERVED, never what is kept.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/tenant_settings.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
// GH #217: refused while the time-tracking feature is switched off for the install.
requireModuleOnJson(connectToDatabase(), 'time-tracking');
requireCapabilityJson(Cap::TICKETS_MANAGE);

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

try {
    $conn = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];

    $conn->beginTransaction();
    try {
        // The install-wide defaults.
        if (isset($in['default']) && is_array($in['default'])) {
            foreach ([SETTING_TIME_TRACKING_UI => 'ui', SETTING_TIME_TRACKING_API => 'api'] as $key => $field) {
                if (!array_key_exists($field, $in['default'])) continue;
                $val = !empty($in['default'][$field]) ? '1' : '0';
                $conn->prepare(
                    "INSERT INTO system_settings (setting_key, setting_value) VALUES (?,?)
                     ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
                )->execute([$key, $val]);
            }
        }

        // Per-company overrides.
        if (isset($in['companies']) && is_array($in['companies'])) {
            foreach ($in['companies'] as $c) {
                $tid = (int) ($c['id'] ?? 0);
                if ($tid <= 0) continue;
                // ⚠️ Only companies this analyst may administer. Without this, an
                // analyst scoped to one client could change another client's.
                if (!analystCanAccessTenant($conn, $analystId, $tid)) continue;

                foreach ([SETTING_TIME_TRACKING_UI => 'ui', SETTING_TIME_TRACKING_API => 'api'] as $key => $field) {
                    if (!array_key_exists($field, $c)) continue;
                    $v = $c[$field];
                    setTenantSetting($conn, $tid, $key, $v === null ? null : (!empty($v) ? '1' : '0'));
                }
            }
        }

        $conn->commit();
    } catch (Throwable $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        throw $e;
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
