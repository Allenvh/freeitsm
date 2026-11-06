<?php
/**
 * API: Remove an asset from a contract (discussion #106).
 * POST { link_id }
 *
 * Deletes the LINK only. The asset and the contract both survive it — this says
 * "this equipment is not on that agreement", never "this equipment is gone".
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/contract_assets.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('contracts');

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

try {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    contractAssetUnlink($conn, (int)$_SESSION['analyst_id'], (int)($in['link_id'] ?? 0));
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
