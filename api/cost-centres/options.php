<?php
/**
 * API: the cost centres a record can be charged to (GH #160, stage 2).
 * GET ?company_id=N[&keep=ID]
 *
 * The active cost centres of company N, plus `keep` when it is the record's
 * current one and has since been made inactive - see includes/cost_centre_assign.php.
 * Shared by the asset, contract and person screens, so it asks for any one of
 * those modules rather than System (where cost centres are managed). A company
 * the analyst cannot reach is refused; with one company, it is always the Default.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/cost_centre_assign.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireAnyModuleAccessJson(['assets', 'contracts', 'tickets']);

try {
    $conn = connectToDatabase();
    $tenantId = costCentreRecordTenant($conn, $_GET['company_id'] ?? null);
    if (isMultiTenant($conn) && !analystCanAccessTenant($conn, (int)$_SESSION['analyst_id'], $tenantId)) {
        echo json_encode(['success' => false, 'error' => 'Company not found']);
        exit;
    }
    $keep = isset($_GET['keep']) && (int)$_GET['keep'] > 0 ? (int)$_GET['keep'] : null;
    echo json_encode(['success' => true, 'cost_centres' => costCentreOptions($conn, $tenantId, $keep)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
