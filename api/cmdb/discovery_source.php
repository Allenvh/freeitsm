<?php
/**
 * API: a CI's From discovery card (GH #199 Part B) - the discovered object it is bound to,
 * read live from the virtual estate, and its earlier bindings.
 *
 * GET ?cmdb_object_id=N
 *
 * The estate lives under Assets, so a reader who cannot open Assets sees {restricted: true}
 * and nothing else - the same rule as the asset card (GH #201).
 *
 * @wiki Hyper-V-Discovery-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/virtual_estate_cmdb.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { http_response_code(401); echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('cmdb');

try {
    $conn = connectToDatabase();
    $analyst = (int)$_SESSION['analyst_id'];
    $ciId = (int)($_GET['cmdb_object_id'] ?? 0);
    if (!analystCanAccessCmdbObject($conn, $analyst, $ciId)) { echo json_encode(['success' => false, 'error' => 'Configuration item not found']); exit; }
    $access = ['assets_read' => analystCanAccessModule($conn, $analyst, 'assets')];
    echo json_encode(['success' => true] + VirtualEstateCmdbService::forCi($conn, ActorContext::fromSession($conn), $access, $ciId));
} catch (Throwable $e) {
    error_log('discovery_source: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Could not load where this item was discovered.']);
}
