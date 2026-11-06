<?php
/**
 * API: protect or release a name, manufacturer or model (GH #200 part D).
 * POST JSON {asset_id, field: hostname|manufacturer|model, protect: true|false}
 *
 * A protected value is never overwritten by the agent or Intune; what they report is
 * shown beside it. Releasing hands the field back to them.
 *
 * @wiki Asset-Review-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/asset_protection.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('assets');

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$assetId = (int)($input['asset_id'] ?? 0);
$field = (string)($input['field'] ?? '');
$protect = !empty($input['protect']);
if ($assetId <= 0) { echo json_encode(['success' => false, 'error' => 'asset_id is required']); exit; }

try {
    $conn = connectToDatabase();
    if (!analystCanAccessAsset($conn, (int)$_SESSION['analyst_id'], $assetId)) {
        echo json_encode(['success' => false, 'error' => 'Asset not found']);
        exit;
    }
    AssetProtectionService::set($conn, ActorContext::fromSession($conn), $assetId, $field, $protect);
    echo json_encode(['success' => true, 'protected' => $protect]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
