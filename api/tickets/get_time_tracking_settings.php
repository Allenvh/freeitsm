<?php
/**
 * API Endpoint: time-tracking settings — the install default and every company's
 * override (discussion #72).
 *
 * Returns the two switches:
 *   ui   — should the time-recording panel appear on a ticket
 *   api  — should the REST API serve time entries for that ticket
 *
 * They are separate on purpose. Hiding the panel is about interface clutter;
 * silently emptying an API endpoint breaks integrations belonging to people who
 * changed nothing. Different decisions, different switches.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/tenant_settings.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
// GH #217: refused while the time-tracking feature is switched off for the install.
requireModuleOnJson(connectToDatabase(), 'time-tracking');
requireCapabilityJson(Cap::TICKETS_MANAGE);

try {
    $conn = connectToDatabase();

    $uiOverrides  = tenantSettingsForKey($conn, SETTING_TIME_TRACKING_UI);
    $apiOverrides = tenantSettingsForKey($conn, SETTING_TIME_TRACKING_API);

    $companies = [];
    // Only worth listing companies when there is more than one. At N=1 this is a
    // single pair of switches with no company column, exactly as it was asked for.
    if (isMultiTenant($conn)) {
        foreach (getAllTenants($conn) as $t) {
            $tid = (int) $t['id'];
            $companies[] = [
                'id'   => $tid,
                'name' => $t['name'],
                // null = follows the install default. NOT the same as false.
                'ui'   => array_key_exists($tid, $uiOverrides)  ? ($uiOverrides[$tid]  !== '0') : null,
                'api'  => array_key_exists($tid, $apiOverrides) ? ($apiOverrides[$tid] !== '0') : null,
            ];
        }
    }

    echo json_encode([
        'success'      => true,
        'multi_tenant' => isMultiTenant($conn),
        'default'      => [
            'ui'  => tenantSettingOn($conn, null, SETTING_TIME_TRACKING_UI, true),
            'api' => tenantSettingOn($conn, null, SETTING_TIME_TRACKING_API, true),
        ],
        'companies'    => $companies,
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
