<?php
/**
 * API Endpoint: save the public web address used in links FreeITSM sends out.
 *
 * Stored as system_settings 'public_base_url'.
 *
 * ⚠️ THE PATH IS KEPT, unlike api/messaging/save_base_url.php which deliberately
 * strips it. That is not an inconsistency to tidy away — the two values are used
 * differently. The messaging one has the app root appended to it afterwards by
 * messagingWebhookUrl(); this one IS the root, so an install at
 * `https://example.com/freeitsm-app` must be able to say so. Strip the path here
 * and every [ticket_url] on such an install points at a page that is not there.
 *
 * The query string and fragment ARE stripped: they are always a paste accident
 * (somebody copies the address bar while looking at a ticket), and keeping them
 * would append a second `?` to every link built from this.
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
    $data = json_decode(file_get_contents('php://input'), true);
    $raw  = trim((string)($data['base_url'] ?? ''));

    $clean = '';
    if ($raw !== '') {
        // A bare host is the natural thing to type; assume https rather than
        // rejecting it, since an install reachable from outside on plain http is
        // the rarer case and the admin can still type http:// explicitly.
        if (!preg_match('#^https?://#i', $raw)) {
            $raw = 'https://' . $raw;
        }
        $parts = parse_url($raw);
        if (empty($parts['host'])) {
            throw new Exception('That does not look like a valid web address.');
        }
        $clean = ($parts['scheme'] ?? 'https') . '://' . $parts['host']
               . (isset($parts['port']) ? ':' . $parts['port'] : '')
               . rtrim((string)($parts['path'] ?? ''), '/');
    }

    $conn = connectToDatabase();
    $stmt = $conn->prepare(
        "INSERT INTO system_settings (setting_key, setting_value)
         VALUES ('public_base_url', :v)
         ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)"
    );
    $stmt->execute([':v' => $clean]);

    echo json_encode(['success' => true, 'base_url' => $clean]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
