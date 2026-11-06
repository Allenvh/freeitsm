<?php
/**
 * API: Run the CMDB data-quality audit for the analyst's current company.
 *
 * Read-only and advisory — it reports, it never edits. See includes/cmdb_audit.php
 * for what each check means and why it is framed around impact analysis.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/cmdb_audit.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

requireModuleAccessJson('cmdb');

try {
    $conn = connectToDatabase();
    $audit = cmdbRunAudit($conn, (int) $_SESSION['analyst_id']);
    echo json_encode(['success' => true, 'audit' => $audit]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
