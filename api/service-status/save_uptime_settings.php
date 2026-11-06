<?php
/**
 * API: save the uptime reporting settings (discussion #59).
 *
 * Two settings only, and both are genuinely nobody else's property:
 *   status_uptime_window_days   the default measurement window
 *   status_uptime_show_portal   whether customers see the figures
 *
 * ⚠️ What COUNTS as downtime is NOT here. That is a property of each impact
 * level (service_impact_levels.counts_as_downtime) and is edited on the Impact
 * levels tab, so a custom level added later is asked the question when it is
 * created rather than needing a second list to be remembered.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/service_uptime.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('service-status');
requireCapabilityJson(Cap::SERVICE_STATUS_UPTIME);   // settings tab — see docs/design/rbac.md

try {
    $conn  = connectToDatabase();
    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    // The window drives a DATE_SUB interval and the number of strip cells built,
    // so it is a choice from a fixed list rather than any integer someone sends.
    $days = (int)($input['window_days'] ?? 0);
    if (!in_array($days, ServiceUptime::WINDOWS, true)) {
        echo json_encode(['success' => false, 'error' => 'Choose one of the offered windows.']);
        exit;
    }
    $portal = !empty($input['show_portal']) ? '1' : '0';

    $upsert = $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value) VALUES (:k, :v)
         ON DUPLICATE KEY UPDATE setting_value = :v2"
    );
    $upsert->execute([':k' => 'status_uptime_window_days', ':v' => (string)$days,  ':v2' => (string)$days]);
    $upsert->execute([':k' => 'status_uptime_show_portal', ':v' => $portal,        ':v2' => $portal]);

    echo json_encode(['success' => true, 'window_days' => $days, 'show_portal' => $portal === '1']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
