<?php
/**
 * Replace the mapping for one connection.
 *
 * The screen sends the whole set, and the service replaces whole map types at a
 * time — a mapping the admin removed has to disappear, and diffing rows to work
 * that out is more code and more ways to be wrong.
 *
 * Body: { connection_id, maps: { project: {...}, issue_type: {...}, priority: {...} } }
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/encryption.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/integrations/integrations.php';

header('Content-Type: application/json');

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Bad request.']);
    exit;
}

$conn = connectToDatabase();

if (!integrationsSchemaReady($conn)) {
    echo json_encode(['success' => false, 'error' => 'Run Database Verification first.']);
    exit;
}

$connectionId = (int)($in['connection_id'] ?? 0);
if ($connectionId <= 0 || !integrationsLoadConnection($conn, $connectionId)) {
    echo json_encode(['success' => false, 'error' => 'That connection no longer exists.']);
    exit;
}

// Only the map types we understand. An unknown type would otherwise be stored
// and then never read by anything, which looks like a saved setting that does
// nothing — worse than refusing it.
$allowed = [INTEGRATION_MAP_PROJECT, INTEGRATION_MAP_ISSUE_TYPE, INTEGRATION_MAP_PRIORITY];
$maps    = [];
foreach ((array)($in['maps'] ?? []) as $type => $rows) {
    if (!in_array($type, $allowed, true) || !is_array($rows)) continue;
    $maps[$type] = $rows;
}

if (!$maps) {
    echo json_encode(['success' => false, 'error' => 'Nothing to save.']);
    exit;
}

try {
    integrationsSaveMaps($conn, $connectionId, $maps);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('save_mapping: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
