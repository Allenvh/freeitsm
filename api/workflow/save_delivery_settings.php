<?php
/**
 * API: set how outbound webhooks are delivered (System > Webhooks).
 *
 * Body: any of { max_attempts, timeout, interrupted } - each saved only when sent.
 *   max_attempts  tries before a delivery is given up as dead (1, 3, 6, 10)
 *   timeout       seconds each try waits for the endpoint (5, 10, 15, 30, 60)
 *   interrupted   what to do with a try the worker never finished: 'retry' or 'stop'
 *
 * The allowed values live beside the reader in includes/webhook_delivery.php,
 * so this endpoint and webhookDeliverySettings() cannot disagree.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once __DIR__ . '/../../includes/admin_api_guard.php';
require_once __DIR__ . '/../../includes/webhook_delivery.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
// Lives under System, so it's admin-only like every other System endpoint.
requireAdminJson(connectToDatabase());

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) $in = [];

// Whitelist rather than accept any value - these are the only ones the page offers.
$save = [];
if (array_key_exists('max_attempts', $in)) {
    if (!in_array((int)$in['max_attempts'], WEBHOOK_ATTEMPTS_ALLOWED, true)) {
        echo json_encode(['success' => false, 'error' => 'Invalid number of attempts']);
        exit;
    }
    $save[WEBHOOK_ATTEMPTS_SETTING] = (string)(int)$in['max_attempts'];
}
if (array_key_exists('timeout', $in)) {
    if (!in_array((int)$in['timeout'], WEBHOOK_TIMEOUT_ALLOWED, true)) {
        echo json_encode(['success' => false, 'error' => 'Invalid timeout']);
        exit;
    }
    $save[WEBHOOK_TIMEOUT_SETTING] = (string)(int)$in['timeout'];
}
if (array_key_exists('interrupted', $in)) {
    if (!in_array($in['interrupted'], [WEBHOOK_INTERRUPTED_RETRY, WEBHOOK_INTERRUPTED_STOP], true)) {
        echo json_encode(['success' => false, 'error' => 'Invalid choice for interrupted deliveries']);
        exit;
    }
    $save[WEBHOOK_INTERRUPTED_SETTING] = (string)$in['interrupted'];
}
if (!$save) {
    echo json_encode(['success' => false, 'error' => 'Nothing to save']);
    exit;
}

try {
    $conn = connectToDatabase();
    $stmt = $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    foreach ($save as $k => $v) $stmt->execute([$k, $v]);

    echo json_encode(['success' => true, 'settings' => webhookDeliverySettings($conn)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
