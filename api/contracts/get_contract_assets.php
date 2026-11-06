<?php
/**
 * API: Equipment covered by a contract (discussion #106).
 * GET ?contract_id=
 *
 * The list is filtered to the companies this analyst can reach — see the header
 * of includes/contract_assets.php for why the asset side is the only side that
 * can answer that, and why no count of the hidden remainder is returned.
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

try {
    $contractId = (int)($_GET['contract_id'] ?? 0);
    if ($contractId <= 0) {
        throw new Exception('contract_id is required');
    }

    $conn = connectToDatabase();
    echo json_encode([
        'success' => true,
        'assets'  => contractAssetsFor($conn, (int)$_SESSION['analyst_id'], $contractId),
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
