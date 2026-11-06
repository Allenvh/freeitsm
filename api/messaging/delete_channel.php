<?php
/**
 * API Endpoint: delete a messaging channel. Past tickets/messages keep their
 * channel_id reference (the row just no longer resolves), so history is intact —
 * only new inbound/outbound on this channel stops.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/messaging/messaging.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// Reached from Tickets → Settings → Messaging (RBAC) or from System →
// Integrations → Slack (admin-only). messagingAdminMayAdministerChannel()
// explains why an admin is allowed here for a Slack channel and nothing else.
$rawIn = file_get_contents('php://input');
$data  = json_decode($rawIn, true);
if (!messagingAdminMayAdministerChannel(connectToDatabase(), (int) ($data['id'] ?? 0))) {
    requireModuleAccessJson('tickets');
    requireCapabilityJson(Cap::TICKETS_MESSAGING);
}

try {
    $id = (int) ($data['id'] ?? 0);
    if ($id <= 0) {
        throw new Exception('Channel ID is required');
    }

    $conn = connectToDatabase();

    // Only a channel this analyst may administer — a channel pinned to a company
    // they can't reach is framed as not-found rather than confirming it exists.
    if (!analystCanAccessChannel($conn, (int) $_SESSION['analyst_id'], $id)) {
        echo json_encode(['success' => false, 'error' => 'Channel not found']);
        exit;
    }

    $conn->prepare("DELETE FROM messaging_channels WHERE id = ?")->execute([$id]);

    echo json_encode(['success' => true, 'message' => 'Channel deleted']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
