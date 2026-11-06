<?php
/**
 * API: what the self-service portal shows about service status (#99).
 *
 * GET                              the current settings
 * POST { updates, mode, days }     save them
 *
 * 🔴 This switch decides what END USERS can read. Turning it on publishes
 * incident titles and every update marked external, so it is gated on the
 * System module rather than on Service Status — the people who administer the
 * install, not everyone who can raise an incident.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
// Administrators only, the same gate every other System endpoint uses. It is an
// authoritative DB check, so a just-demoted analyst is stopped even on a stale
// session — which matters for a switch that decides what customers can read.
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/service_status_portal.php';

header('Content-Type: application/json');

try {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $cfg = ssPortalSettings($conn);
        echo json_encode([
            'success' => true,
            'updates' => $cfg['service_status_portal_updates'] === '1',
            'mode'    => $cfg['service_status_portal_mode'],
            'days'    => (int)$cfg['service_status_portal_days'],
        ]);
        exit;
    }

    $in = json_decode(file_get_contents('php://input'), true);
    if (!is_array($in)) {
        throw new Exception('Invalid JSON');
    }

    $mode = in_array(($in['mode'] ?? ''), ['open', 'recent', 'all'], true) ? $in['mode'] : 'recent';
    // Clamped rather than trusted. A window of 0 would mean "recent" quietly
    // behaved like "open", and a huge one is just "all" with extra steps.
    $days = max(1, min(365, (int)($in['days'] ?? 7)));

    $save = $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value, updated_datetime)
              VALUES (?, ?, UTC_TIMESTAMP())
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value),
                                 updated_datetime = UTC_TIMESTAMP()"
    );
    $save->execute(['service_status_portal_updates', !empty($in['updates']) ? '1' : '0']);
    $save->execute(['service_status_portal_mode', $mode]);
    $save->execute(['service_status_portal_days', (string)$days]);

    // Read back rather than echoing what was sent, so the page shows what was
    // actually stored — including the clamped day count.
    $cfg = ssPortalSettings($conn);
    echo json_encode([
        'success' => true,
        'updates' => $cfg['service_status_portal_updates'] === '1',
        'mode'    => $cfg['service_status_portal_mode'],
        'days'    => (int)$cfg['service_status_portal_days'],
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
