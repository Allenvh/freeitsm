<?php
/**
 * API Endpoint: Remove a user from an asset.
 * Thin UI adapter over AssetsService::unassignUser (deletes the users_assets
 * row, custody checkin, and audit trail). `skip_audit` suppresses the history
 * row when this removal is the first half of a re-assign (the assign call then
 * logs the transition).
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
$skipAudit = $data['skip_audit'] ?? false;

if (!$assetId || (!$userId && !$analystId)) {
    echo json_encode(['success' => false, 'error' => 'Asset ID and either a User ID or an Analyst ID are required']);
    exit;
}

try {
    $conn = connectToDatabase();
    // Multi-tenancy: refuse to touch an asset outside this analyst's companies.
    if (!analystCanAccessAsset($conn, (int)$_SESSION['analyst_id'], (int)$assetId)) {
        throw new Exception('Asset not found');
    }
    if ($analystId) {
        AssetsService::unassignAnalyst($conn, ActorContext::fromSession($conn), (int)$assetId, (int)$analystId, (bool)$skipAudit);
    } else {
        AssetsService::unassignUser($conn, ActorContext::fromSession($conn), (int)$assetId, (int)$userId, (bool)$skipAudit);
    }
    echo json_encode(['success' => true, 'message' => 'Removed from asset successfully']);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
