<?php
/**
 * API: CMDB -> Settings -> Asset card (GH #201) - which details of a linked asset a CI's
 * Asset card shows.
 *
 * GET               {fields: [...]}  the ticked details (all of them when never saved)
 * POST {fields: []}                 save; needs the Asset card capability
 *
 * A view setting only. AssetCiLinkService never sends an asset's details to someone who
 * cannot open Assets, whatever is ticked here.
 *
 * @wiki Asset-CI-Link-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/asset_ci_links.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { http_response_code(401); echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('cmdb');

try {
    $conn = connectToDatabase();
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        csrfRequireWriteMethod();
        requireCapabilityJson(Cap::CMDB_ASSET_CARD, $conn);
        $in = json_decode(file_get_contents('php://input'), true) ?: [];
        $fields = AssetCiLinkService::saveCardFields($conn, is_array($in['fields'] ?? null) ? $in['fields'] : []);
        echo json_encode(['success' => true, 'fields' => $fields]);
        exit;
    }
    echo json_encode(['success' => true, 'fields' => AssetCiLinkService::cardFields($conn), 'all' => AssetCiLinkService::CARD_FIELDS]);
} catch (Throwable $e) {
    error_log('asset_card_settings: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'The setting could not be loaded or saved.']);
}
