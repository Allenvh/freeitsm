<?php
/**
 * API: Link an asset to a ticket (discussion #57).
 *
 * Idempotent — a repeat link returns success with already_linked, so clicking
 * twice is quiet rather than a SQL error.
 *
 * Mirrors save_ticket_cmdb_object.php, including its hard-won lesson: reaching
 * both ends is not enough on a multi-company install, because an all-access
 * analyst can reach both. The ticket and the asset must belong to the SAME
 * company, or the link itself becomes the leak.
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
    $ticketId = isset($data['ticket_id']) ? (int)$data['ticket_id'] : 0;
    $assetId  = isset($data['asset_id'])  ? (int)$data['asset_id']  : 0;
    if ($ticketId <= 0 || $assetId <= 0) {
        throw new Exception('ticket_id and asset_id are required');
    }

    $conn      = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    // Multi-tenancy: only touch a ticket this analyst can reach.
    if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
        throw new Exception('Ticket not found');
    }

    // Verify both rows exist before inserting — a clearer error than an FK failure.
    $check = $conn->prepare("SELECT 1 FROM tickets WHERE id = ?");
    $check->execute([$ticketId]);
    if (!$check->fetchColumn()) throw new Exception('Ticket not found');

    $check = $conn->prepare("SELECT 1 FROM assets WHERE id = ?");
    $check->execute([$assetId]);
    if (!$check->fetchColumn()) throw new Exception('Asset not found');

    // The ticket gate says nothing about the asset. Without this, any asset id
    // could be attached to a ticket the analyst legitimately owns, pulling another
    // company's hostname and serial into their reading pane. Framed as not-found.
    if (!analystCanAccessAsset($conn, $analystId, $assetId)) {
        throw new Exception('Asset not found');
    }

    // Both ends reachable is still not enough for an all-access analyst.
    if (isMultiTenant($conn)) {
        $tt = $conn->prepare("SELECT tenant_id FROM tickets WHERE id = ?");
        $tt->execute([$ticketId]);
        $ticketTenant = $tt->fetchColumn();
        $at = $conn->prepare("SELECT tenant_id FROM assets WHERE id = ?");
        $at->execute([$assetId]);
        $assetTenant = $at->fetchColumn();
        $norm = fn($v) => ($v === null || $v === false) ? getDefaultTenantId($conn) : (int)$v;
        if ($norm($ticketTenant) !== $norm($assetTenant)) {
            throw new Exception('That asset belongs to a different company');
        }
    }

    try {
        $ins = $conn->prepare(
            "INSERT INTO ticket_assets (ticket_id, asset_id, created_datetime, created_by_analyst_id)
             VALUES (?, ?, UTC_TIMESTAMP(), ?)"
        );
        $ins->execute([$ticketId, $assetId, $analystId]);
        echo json_encode(['success' => true, 'id' => (int)$conn->lastInsertId(), 'already_linked' => false]);
    } catch (PDOException $pe) {
        if ($pe->errorInfo[1] == 1062) {
            echo json_encode(['success' => true, 'already_linked' => true]);
            exit;
        }
        throw $pe;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
