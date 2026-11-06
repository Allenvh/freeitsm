<?php
/**
 * API Endpoint: a person confirms two assets are connected (GH #200 part C)
 * POST JSON: {asset_id, other_asset_id}
 *
 * Plain module access, like assigning an asset to somebody: recording what is plugged
 * into what is ordinary analyst work. Both assets must be reachable and in one company.
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

$input   = json_decode(file_get_contents('php://input'), true) ?: [];
$assetId = (int)($input['asset_id'] ?? 0);
$otherId = (int)($input['other_asset_id'] ?? 0);
if ($assetId <= 0 || $otherId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Two assets are required']);
    exit;
}

try {
    require_once '../../includes/tenancy.php';
    require_once '../../includes/services/asset_connections.php';
    $conn = connectToDatabase();
    $analyst = (int)$_SESSION['analyst_id'];
    if (!analystCanAccessAsset($conn, $analyst, $assetId) || !analystCanAccessAsset($conn, $analyst, $otherId)) {
        echo json_encode(['success' => false, 'error' => 'Asset not found']);
        exit;
    }
    $res = AssetConnectionsService::connect($conn, ActorContext::fromSession($conn), $assetId, $otherId);
    echo json_encode(['success' => true, 'id' => $res['id']]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
