<?php
/**
 * API: List companies (tenants).
 * GET - returns every company. "Company" is the user-facing word for a tenant;
 * the underlying table/code stays `tenants`.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();

    // The email domains registered to each company (one query, grouped in PHP),
    // so the list can show them without a round-trip per company. Degrades to
    // empty if the table isn't there yet.
    $domainsByTenant = [];
    try {
        foreach ($conn->query("SELECT tenant_id, domain FROM tenant_domains ORDER BY domain") as $row) {
            $domainsByTenant[(int)$row['tenant_id']][] = $row['domain'];
        }
    } catch (Exception $e) {
        $domainsByTenant = [];
    }

    $companies = [];
    foreach (getAllTenants($conn) as $t) {
        $id = (int)$t['id'];
        $companies[] = [
            'id'         => $id,
            'name'       => $t['name'],
            'is_default' => (bool)$t['is_default'],
            'is_active'  => (bool)$t['is_active'],
            'domains'    => $domainsByTenant[$id] ?? [],
        ];
    }

    echo json_encode(['success' => true, 'companies' => $companies]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
