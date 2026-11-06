<?php
/**
 * API: Replace which field sets an asset TYPE carries.
 * Every Television gets these fields.
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
    AssetFieldsService::setTypeSets(
        $conn,
        (int)($data['asset_type_id'] ?? 0),
        is_array($data['set_ids'] ?? null) ? $data['set_ids'] : []
    );
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
