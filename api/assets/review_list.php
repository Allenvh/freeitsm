<?php
/**
 * API: the asset review list (GH #200 part D) - Assets -> Settings -> Review.
 * GET ?which=open|decided
 *
 * Finds the cases that come from the assets themselves first (AssetReviewService::sync:
 * shared serials, reused hostnames) for the active company, then lists them with the
 * unmatched and ambiguous peripherals tools have reported.
 *
 * @wiki Asset-Review-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/asset_review.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_RECONCILIATION);

try {
    $conn = connectToDatabase();
    $analyst = (int)$_SESSION['analyst_id'];
    if (!AssetReviewService::ready($conn)) {
        echo json_encode(['success' => true, 'items' => [], 'needs_verification' => true]);
        exit;
    }
    AssetReviewService::sync($conn, getActiveTenantId($conn, $analyst));
    $which = ($_GET['which'] ?? '') === 'decided' ? 'decided' : 'open';
    echo json_encode(['success' => true, 'items' => AssetReviewService::listFor($conn, $analyst, $which)]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
