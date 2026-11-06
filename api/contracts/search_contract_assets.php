<?php
/**
 * API: Assets that could be added to a contract (discussion #106).
 * GET ?contract_id=&q=
 *
 * Scoped to what the analyst can reach, and excludes what is already linked —
 * offering something already attached is only a way to produce a no-op.
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
        'assets'  => contractAssetSearch(
            $conn,
            (int)$_SESSION['analyst_id'],
            $contractId,
            trim((string)($_GET['q'] ?? ''))
        ),
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
