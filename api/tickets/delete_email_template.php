<?php
/**
 * API Endpoint: Delete an email template
 * POST: { id }
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
requireCapabilityJson(Cap::TICKETS_EMAIL_TEMPLATES);   // settings tab — see docs/design/rbac.md

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Template ID is required']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Sender rules first (#80). There is no foreign key, so nothing else would
    // remove them, and an AUTO_INCREMENT id can be reissued after a restart —
    // which would silently attach a deleted template's rules to a new one.
    $conn->prepare("DELETE FROM ticket_email_template_rules WHERE template_id = ?")->execute([$id]);

    $sql = "DELETE FROM ticket_email_templates WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
