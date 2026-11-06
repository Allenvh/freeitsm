<?php
/**
 * API Endpoint: Assign a user to an asset.
 * Thin UI adapter over AssetsService::assignUser (creates the users_assets row,
 * custody checkout, and audit trail). On a re-assign the UI passes
 * `previous_user_id` so the audit records the "was X, now Y" transition.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/assets.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

requireModuleAccessJson('assets');

$data = json_decode(file_get_contents('php://input'), true) ?: [];

$assetId   = $data['asset_id'] ?? null;
$userId    = $data['user_id'] ?? null;
$analystId = $data['analyst_id'] ?? null;

// A holder is EITHER a requester or an analyst. Sending both is rejected rather
// than resolved by picking one: a caller that sends two has a bug, and silently
// honouring one of them hides it until somebody wonders why the other name never
// appeared.
if (!$assetId || (!$userId && !$analystId)) {
    echo json_encode(['success' => false, 'error' => 'Asset ID and either a User ID or an Analyst ID are required']);
    exit;
}
if ($userId && $analystId) {
    echo json_encode(['success' => false, 'error' => 'Provide a User ID or an Analyst ID, not both']);
    exit;
}

try {
    $conn = connectToDatabase();
    // Multi-tenancy: refuse to touch an asset outside this analyst's companies.
    if (!analystCanAccessAsset($conn, (int)$_SESSION['analyst_id'], (int)$assetId)) {
        throw new Exception('Asset not found');
    }
    if ($analystId) {
        AssetsService::assignAnalyst($conn, ActorContext::fromSession($conn), (int)$assetId, $data);
        echo json_encode(['success' => true, 'message' => 'Analyst assigned successfully']);
    } else {
        AssetsService::assignUser($conn, ActorContext::fromSession($conn), (int)$assetId, $data);
        echo json_encode(['success' => true, 'message' => 'User assigned successfully']);
    }
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
