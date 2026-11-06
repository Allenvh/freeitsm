<?php
/**
 * API Endpoint: an asset's connected assets, from either end (GH #200 part C)
 * GET: ?asset_id=123
 *
 * @output {success, current:[{other, rows:[...]}], history:[...]} - see AssetConnectionsService::listFor()
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

$assetId = (int)($_GET['asset_id'] ?? 0);
if ($assetId <= 0) {
    echo json_encode(['success' => false, 'error' => 'asset_id is required']);
    exit;
}

try {
    require_once '../../includes/tenancy.php';
    require_once '../../includes/services/asset_connections.php';
    $conn = connectToDatabase();
    if (!analystCanAccessAsset($conn, (int)$_SESSION['analyst_id'], $assetId)) {
        echo json_encode(['success' => false, 'error' => 'Asset not found']);
        exit;
    }
    $list = AssetConnectionsService::listFor($conn, ActorContext::fromSession($conn), $assetId);
    echo json_encode(['success' => true] + $list);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
