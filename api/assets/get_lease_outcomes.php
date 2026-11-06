<?php
/**
 * API Endpoint: Lease outcomes (3.5.0) - what became of a leased asset. One list
 * for every company, managed in Assets -> Settings -> Leasing.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/leases.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

try {
    $conn = connectToDatabase();
    echo json_encode(['success' => true, 'lease_outcomes' => leaseOutcomes($conn, empty($_GET['manage']))]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
