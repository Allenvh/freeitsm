<?php
/**
 * API: Unlink an asset from a ticket (discussion #57). Accepts either the link
 * row id (link_id), or a ticket_id + asset_id pair.
 *
 * Mirrors delete_ticket_cmdb_object.php. The gate is on the TICKET, not the
 * asset: unlinking removes a row from the ticket, and an analyst who can edit
 * the ticket may correct what is attached to it.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
// GH #217: refused while the assets module is switched off for the install.
requireModuleOnJson(connectToDatabase(), 'assets');

try {
    $data     = json_decode(file_get_contents('php://input'), true) ?: [];
    $linkId   = isset($data['link_id'])   ? (int)$data['link_id']   : 0;
    $ticketId = isset($data['ticket_id']) ? (int)$data['ticket_id'] : 0;
    $assetId  = isset($data['asset_id'])  ? (int)$data['asset_id']  : 0;

    $conn      = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    if ($linkId > 0) {
        // Resolve the link's ticket first and gate on that, so a link id alone
        // cannot be used to probe or edit another company's ticket.
        $own = $conn->prepare("SELECT ticket_id FROM ticket_assets WHERE id = ?");
        $own->execute([$linkId]);
        $linkTicketId = $own->fetchColumn();
        if ($linkTicketId === false || !analystCanAccessTicket($conn, $analystId, (int)$linkTicketId)) {
            throw new Exception('Ticket not found');
        }
        $stmt = $conn->prepare("DELETE FROM ticket_assets WHERE id = ?");
        $stmt->execute([$linkId]);
    } elseif ($ticketId > 0 && $assetId > 0) {
        if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
            throw new Exception('Ticket not found');
        }
        $stmt = $conn->prepare("DELETE FROM ticket_assets WHERE ticket_id = ? AND asset_id = ?");
        $stmt->execute([$ticketId, $assetId]);
    } else {
        throw new Exception('Pass link_id OR (ticket_id + asset_id)');
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
