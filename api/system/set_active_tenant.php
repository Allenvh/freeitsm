<?php
/**
 * API Endpoint: set the analyst's active company (tenant) context.
 *
 * Stores the chosen tenant id in the session so the rest of the app can scope
 * to it. Validates that the analyst is actually allowed to access that company.
 *
 * Requires a WRITABLE session — note the plain session_start() (NOT
 * read_and_close), because we write $_SESSION['active_tenant_id'].
 */
session_start();
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$tenantId = isset($data['tenant_id']) ? (int) $data['tenant_id'] : 0;

if ($tenantId < 1) {
    echo json_encode(['success' => false, 'error' => 'tenant_id is required']);
    exit;
}

try {
    $conn = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];

    if (!analystCanAccessTenant($conn, $analystId, $tenantId)) {
        echo json_encode(['success' => false, 'error' => 'You do not have access to that company']);
        exit;
    }

    setActiveTenantId($tenantId);

    echo json_encode(['success' => true, 'tenant_id' => $tenantId]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
