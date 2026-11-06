<?php
/**
 * API: mark a parked row in the holding area as dealt with.
 *
 * The row itself is kept — it is the record of what went wrong and what the
 * source actually said. Resolving only takes it off the "needs attention" list,
 * so the count means something.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/asset_import.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_IMPORT);

try {
    $conn = connectToDatabase();
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $ids  = is_array($data['ids'] ?? null) ? $data['ids'] : [$data['id'] ?? 0];

    $n = 0;
    foreach ($ids as $id) {
        $id = (int)$id;
        if ($id > 0) {
            AssetImportService::resolveEntry($conn, $id);
            $n++;
        }
    }
    echo json_encode(['success' => true, 'resolved' => $n]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
