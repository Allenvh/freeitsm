<?php
/**
 * API Endpoint: how fresh each inventory list on an asset is (GH #200 Part B)
 * GET: ?asset_id=123
 *
 * Per source that reports this asset (the agent, REST tools) and per section (software,
 * volumes, physical drives, network adapters, devices): when its last good data was
 * observed, and what the last attempt came to - succeeded, empty, skipped or failed.
 * Plus how many rows each source holds, so the page can tell "never collected" (no record,
 * no rows) from "reported before freshness was recorded" (rows, no record).
 *
 * Presentation reads only - the type's layout plays no part (wiki Asset-Type-Profiles-Design,
 * "hidden is not not-applicable").
 *
 * @output {success, stale_days, sources:[{source, source_device_ref, sections:{name:{observed_at, imported_at, last_outcome, last_error, last_attempt_at}}}], rows:{source:{section:n}}}
 * @wiki Inventory-Freshness-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

$assetId = (int)($_GET['asset_id'] ?? 0);
if ($assetId <= 0) {
    echo json_encode(['success' => false, 'error' => 'asset_id is required']);
    exit;
}

try {
    require_once '../../includes/tenancy.php';
    require_once '../../includes/services/asset_inventory.php';
    $conn = connectToDatabase();
    // Multi-tenancy: only serve child data for an asset in this analyst's companies.
    if (!analystCanAccessAsset($conn, (int)$_SESSION['analyst_id'], $assetId)) {
        echo json_encode(['success' => false, 'error' => 'Asset not found']);
        exit;
    }

    $sources = AssetInventoryService::provenance($conn, ActorContext::fromSession($conn), $assetId);

    // Rows per stored source tag ('agent', 'intune', 'rest:<name>', 'manual', ...) and section.
    $rows = [];
    foreach (['software' => ['software_inventory_detail', 'host_id', true], 'disks' => ['asset_disks', 'asset_id', true],
              'network_adapters' => ['asset_network_adapters', 'asset_id', true],
              'physical_disks' => ['asset_physical_disks', 'asset_id', false], 'devices' => ['asset_devices', 'asset_id', false]] as $section => [$table, $host, $bySource]) {
        try {
            $stmt = $conn->prepare($bySource
                ? "SELECT source, COUNT(*) FROM $table WHERE $host = ? GROUP BY source"
                : "SELECT 'agent', COUNT(*) FROM $table WHERE $host = ?");   // only the agent writes these
            $stmt->execute([$assetId]);
            foreach ($stmt->fetchAll(PDO::FETCH_NUM) as [$source, $n]) {
                if ((int)$n > 0) { $rows[$source][$section] = (int)$n; }
            }
        } catch (PDOException $e) {
            // asset_physical_disks before Database Verification: simply none.
        }
    }

    // GH #200 part D: the open review cases this asset is part of, for the line on Key info.
    require_once '../../includes/services/asset_review.php';
    // ...and which of its name, manufacturer and model are protected (also part D).
    require_once '../../includes/services/asset_protection.php';

    echo json_encode([
        'success'    => true,
        'stale_days' => AssetInventoryService::staleDays($conn),
        'sources'    => $sources,
        'rows'       => (object)$rows,
        'review'     => AssetReviewService::openFor($conn, $assetId),
        'protected'  => (object)AssetProtectionService::forAsset($conn, $assetId),
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
