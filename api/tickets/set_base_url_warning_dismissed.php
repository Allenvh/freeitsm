<?php
/**
 * API Endpoint: acknowledge the "no public web address is set" warning.
 *
 * Stored as system_settings 'public_base_url_warning_dismissed'.
 *
 * WHY THIS IS DISMISSIBLE AT ALL. The warning fires when a template uses
 * [ticket_url] and nothing is configured, which is very often a real fault — but
 * not always. An install whose templates are only ever sent by an analyst pressing
 * something, on a single host with no reverse proxy, gets a working link from the
 * request every time. Telling that administrator they have a problem on every visit
 * for the rest of the install's life is how a warning stops being read, taking the
 * ones that matter with it. Same rule as the mailbox health marks: warnings can be
 * acknowledged, errors never (see includes/mailbox_health.php).
 *
 * Dismissing is per install rather than per person, because the thing being
 * acknowledged is a fact about the install, not a preference. Setting the address
 * later resolves it for everyone regardless of what was dismissed.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_EMAIL_TEMPLATES);

try {
    $data      = json_decode(file_get_contents('php://input'), true);
    $dismissed = !empty($data['dismissed']) ? '1' : '0';

    // Whitelisted rather than free-form: this writes a system setting, and letting
    // the browser name the key would let it write any of them.
    $keys = [
        'base_url'       => 'public_base_url_warning_dismissed',
        'template_scope' => 'template_scope_warning_dismissed',   // no catch-all template (#80)
    ];
    $which = (string)($data['warning'] ?? 'base_url');
    if (!isset($keys[$which])) {
        throw new Exception('Unknown warning.');
    }

    $conn = connectToDatabase();
    $stmt = $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value)
         VALUES (:k, :v)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    $stmt->execute([':k' => $keys[$which], ':v' => $dismissed]);

    echo json_encode(['success' => true, 'dismissed' => $dismissed === '1']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
