<?php
/**
 * API: Delete an SLA breach notification rule.
 * POST { id: <int> } — soft-delete not used (rules can be toggled inactive
 * instead if the admin wants to keep the config).
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
// GH #217: refused while the sla feature is switched off for the install.
requireModuleOnJson(connectToDatabase(), 'sla');
requireCapabilityJson(Cap::TICKETS_SLA);   // settings tab — see docs/design/rbac.md

try {
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $id = (int)($data['id'] ?? 0);
    if ($id <= 0) throw new Exception('id is required');

    $conn = connectToDatabase();
    $stmt = $conn->prepare("DELETE FROM sla_notification_rules WHERE id = ?");
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
