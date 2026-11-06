<?php
/**
 * API: manufacturer and model names (GH #200 part D) - Assets -> Settings ->
 * Discovery & reconciliation.
 * GET                                   -> {mappings, names: {manufacturer: [{name, n}], model: [...]}}
 * POST {action: save, field, from, to}  -> show `from` as `to`
 * POST {action: delete, id}
 *
 * Only how values are SHOWN changes; assets keep what was reported (AssetNameMapService).
 *
 * @wiki Asset-Review-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/asset_name_map.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_RECONCILIATION);

try {
    $conn = connectToDatabase();
    if (!AssetNameMapService::ready($conn)) {
        echo json_encode(['success' => false, 'error' => 'Run Database Verification first.']);
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $in = json_decode(file_get_contents('php://input'), true) ?: [];
        if (($in['action'] ?? '') === 'delete') {
            AssetNameMapService::delete($conn, (int)($in['id'] ?? 0));
            echo json_encode(['success' => true]);
        } else {
            $id = AssetNameMapService::save($conn, ActorContext::fromSession($conn), (string)($in['field'] ?? ''), (string)($in['from'] ?? ''), (string)($in['to'] ?? ''));
            echo json_encode(['success' => true, 'id' => $id]);
        }
        exit;
    }
    echo json_encode(['success' => true, 'mappings' => AssetNameMapService::all($conn), 'names' => [
        'manufacturer' => AssetNameMapService::namesInUse($conn, 'manufacturer'),
        'model'        => AssetNameMapService::namesInUse($conn, 'model'),
    ]]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
