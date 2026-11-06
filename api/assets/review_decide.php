<?php
/**
 * API: decide a review case (GH #200 part D).
 * POST JSON {id, decision: different | ignored | open | create}
 *   different  two assets that really are separate - stop asking
 *   ignored    not worth deciding
 *   open       undo a decision
 *   create     an unmatched device: create the asset and open the connection the tool saw
 * Merging is review_merge.php.
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

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$id = (int)($input['id'] ?? 0);
$decision = (string)($input['decision'] ?? '');
if ($id <= 0) { echo json_encode(['success' => false, 'error' => 'id is required']); exit; }

try {
    $conn = connectToDatabase();
    $ctx = ActorContext::fromSession($conn);
    $analyst = (int)$_SESSION['analyst_id'];
    $res = $decision === 'create'
        ? AssetReviewService::createPeripheral($conn, $ctx, $analyst, $id)
        : AssetReviewService::decide($conn, $ctx, $analyst, $id, $decision);
    echo json_encode(['success' => true] + $res);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
