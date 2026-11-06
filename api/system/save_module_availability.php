<?php
/**
 * API: switch a module, or a Tickets feature, on or off for the whole install (GH #217).
 * Admins only.
 * POST { module_key: 'tasks', enabled: false }
 * POST { module_key: 'sla', enabled: true, send_missed: false }
 *
 * Writes system_settings 'modules_disabled' - the list of switched-off modules and
 * Tickets features (TICKET_FEATURES); see includes/module_availability.php for why
 * only those are stored. Tickets and System are refused: they cannot be switched off.
 * Nothing else changes - no data is touched, no grant is removed - so switching back
 * on restores it as it was.
 *
 * SLA back on: unless send_missed is true, one quiet breach-check run records every
 * warning and breach that is already due as announced, without sending it - so the
 * next real run does not email everything that crossed a line while SLA was off.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

$input      = json_decode(file_get_contents('php://input'), true) ?: [];
$key        = (string) ($input['module_key'] ?? '');
$enabled    = !empty($input['enabled']);
$sendMissed = !empty($input['send_missed']);

try {
    if (!array_key_exists($key, getModuleRegistry()) && !isset(TICKET_FEATURES[$key])) {
        throw new InvalidArgumentException('Unknown module.');
    }
    if (in_array($key, MODULES_ALWAYS_ON, true)) {
        throw new InvalidArgumentException('This module cannot be switched off.');
    }
    $conn = connectToDatabase();
    $wasOff = !moduleIsEnabled($conn, $key);
    $off = disabledModules($conn);
    $off = $enabled ? array_values(array_diff($off, [$key])) : array_values(array_unique(array_merge($off, [$key])));
    sort($off);
    $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('modules_disabled', ?)
                    ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")->execute([json_encode($off)]);
    disabledModules($conn, true);   // the rest of this request sees the new list

    $quietlyMarked = null;
    if ($key === 'sla' && $enabled && $wasOff && !$sendMissed) {
        require_once '../../includes/sla_notifications.php';
        $quietlyMarked = sla_run_breach_check($conn, true)['marked'];
    }
    echo json_encode(['success' => true, 'disabled' => $off, 'sla_quietly_marked' => $quietlyMarked]);
} catch (InvalidArgumentException $e) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
