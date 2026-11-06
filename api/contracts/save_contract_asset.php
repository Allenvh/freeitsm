<?php
/**
 * API: Link an asset to a contract, or change the note on an existing link (#106).
 *
 * POST { contract_id, asset_id, reference? }   link, or update the note
 * POST { link_id, reference }                  change the note only
 *
 * ⚠️ The asset id is re-checked against what this analyst can reach. It arrived
 * in a request body, not from the list this server rendered — a scoped list has
 * never been a substitute for a gate.
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

    $analystId = (int)$_SESSION['analyst_id'];
    $reference = array_key_exists('reference', $in) ? (string)$in['reference'] : null;

    if (!empty($in['link_id'])) {
        contractAssetSetReference($conn, $analystId, (int)$in['link_id'], $reference);
        echo json_encode(['success' => true, 'link_id' => (int)$in['link_id']]);
        exit;
    }

    $linkId = contractAssetLink(
        $conn,
        $analystId,
        (int)($in['contract_id'] ?? 0),
        (int)($in['asset_id'] ?? 0),
        $reference
    );
    echo json_encode(['success' => true, 'link_id' => $linkId]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
