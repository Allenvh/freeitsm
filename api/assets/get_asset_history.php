<?php
/**
 * API Endpoint: Get asset change history
 * Returns all history entries for an asset
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';
I18n::initFromSession();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

$assetId = $_GET['asset_id'] ?? null;

if (!$assetId) {
    echo json_encode(['success' => false, 'error' => 'Asset ID required']);
    exit;
}

try {
    require_once '../../includes/tenancy.php';
    $conn = connectToDatabase();
    // Multi-tenancy: only serve child data for an asset in this analyst's companies.
    if (!analystCanAccessAsset($conn, (int)$_SESSION['analyst_id'], (int)$assetId)) {
        echo json_encode(['success' => false, 'error' => 'Asset not found']);
        exit;
    }

    $sql = "SELECT
                ah.id,
                ah.asset_id,
                ah.analyst_id,
                ah.field_name,
                ah.old_value,
                ah.new_value,
                ah.created_datetime,
                a.full_name as analyst_name
            FROM asset_history ah
            LEFT JOIN analysts a ON ah.analyst_id = a.id
            WHERE ah.asset_id = ?
            ORDER BY ah.created_datetime DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$assetId]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // GH #201: configuration item link rows hold 'ci:<id>'. Named only for a reader who can
    // open the CMDB - the history must not reveal a record the reader cannot open.
    require_once '../../includes/services/asset_ci_links.php';
    $cmdbRead = analystCanAccessModule($conn, (int)$_SESSION['analyst_id'], 'cmdb');
    $restricted = t('common.ci_link.restricted_ci');
    foreach ($history as &$h) {
        if ($h['field_name'] === 'cmdb_link') {
            $h['old_value'] = AssetCiLinkService::historyValue($conn, $h['old_value'], $cmdbRead, true, $restricted);
            $h['new_value'] = AssetCiLinkService::historyValue($conn, $h['new_value'], $cmdbRead, true, $restricted);
        }
    }
    unset($h);

    echo json_encode([
        'success' => true,
        'history' => $history
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

?>
