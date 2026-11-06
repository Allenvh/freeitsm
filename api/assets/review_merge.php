<?php
/**
 * API: merge two assets from the review list (GH #200 part D).
 * POST JSON {id, keep_asset_id, merge_asset_id, ci_links_seen?: [link ids], ci_link_keep?: link id}
 *
 * GH #201: ci_links_seen is what the merge window showed (ci_link.php?merge_preview=1); if both
 * assets are linked to a configuration item, ci_link_keep is the link the person chose to keep.
 *
 * Only from a case that names exactly these two assets (same serial, or a reused name),
 * by someone with the reconciliation capability. Everything on the merged asset moves to
 * the kept one - see AssetMergeService. Cannot be undone.
 *
 * @wiki Asset-Review-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/asset_merge.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_RECONCILIATION);

$input = json_decode(file_get_contents('php://input'), true) ?: [];
$itemId = (int)($input['id'] ?? 0);
$keep = (int)($input['keep_asset_id'] ?? 0);
$gone = (int)($input['merge_asset_id'] ?? 0);
if ($itemId <= 0 || $keep <= 0 || $gone <= 0) { echo json_encode(['success' => false, 'error' => 'A review case and two assets are required']); exit; }

try {
    $conn = connectToDatabase();
    $analyst = (int)$_SESSION['analyst_id'];
    $item = AssetReviewService::item($conn, $analyst, $itemId);
    $pair = [$item['asset_id'], $item['other_asset_id']];
    sort($pair);
    $asked = [$keep, $gone];
    sort($asked);
    if ($item['status'] !== 'open' || !in_array($item['kind'], AssetReviewService::PAIR_KINDS, true) || $pair !== $asked) {
        echo json_encode(['success' => false, 'error' => 'This case is not about those two assets, or has already been decided.']);
        exit;
    }
    if (!analystCanAccessAsset($conn, $analyst, $keep) || !analystCanAccessAsset($conn, $analyst, $gone)) {
        echo json_encode(['success' => false, 'error' => 'Asset not found']);
        exit;
    }
    $ciLinks = is_array($input['ci_links_seen'] ?? null)
        ? ['seen' => $input['ci_links_seen'], 'keep_link_id' => isset($input['ci_link_keep']) ? (int)$input['ci_link_keep'] : null]
        : null;
    $res = AssetMergeService::merge($conn, ActorContext::fromSession($conn), $keep, $gone, $itemId, $ciLinks);
    echo json_encode(['success' => true] + $res);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
