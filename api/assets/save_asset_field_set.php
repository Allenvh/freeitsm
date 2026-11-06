<?php
/**
 * API: Create or update a field SET, and (optionally) replace its field list in
 * the same request — so one drag-reorder is one call and the order can never be
 * half-applied.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/asset_fields.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_FIELDS);

try {
    $conn = connectToDatabase();
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $res  = AssetFieldsService::saveSet($conn, ActorContext::fromSession($conn), $data);
    if (isset($data['fields']) && is_array($data['fields'])) {
        AssetFieldsService::setSetFields($conn, (int)$res['id'], $data['fields']);
    }
    echo json_encode(['success' => true] + $res);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
