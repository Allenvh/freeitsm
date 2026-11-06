<?php
/**
 * API Endpoint: a person ends a connection a person confirmed (GH #200 part C)
 * POST JSON: {connection_id}
 *
 * A tool's observed connection is the tool's to end; the service refuses it here.
 *
 * @wiki Connected-Assets-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$connectionId = (int)($input['connection_id'] ?? 0);
if ($connectionId <= 0) {
    echo json_encode(['success' => false, 'error' => 'connection_id is required']);
    exit;
}

try {
    require_once '../../includes/tenancy.php';
    require_once '../../includes/services/asset_connections.php';
    $conn = connectToDatabase();
    $st = $conn->prepare('SELECT asset_id, connected_asset_id FROM asset_connections WHERE id = ?');
    $st->execute([$connectionId]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    $analyst = (int)$_SESSION['analyst_id'];
    if (!$row || !analystCanAccessAsset($conn, $analyst, (int)$row['asset_id']) || !analystCanAccessAsset($conn, $analyst, (int)$row['connected_asset_id'])) {
        echo json_encode(['success' => false, 'error' => 'Connection not found']);
        exit;
    }
    AssetConnectionsService::disconnect($conn, ActorContext::fromSession($conn), $connectionId);
    echo json_encode(['success' => true]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
