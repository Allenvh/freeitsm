<?php
/**
 * API: the RFP list - the active company's RFPs, or every company the analyst can see
 * under "All companies" (rfpListSql). Most recently updated first.
 *
 * @input  search  optional text to match against the RFP name
 * @output {success, rfps: [...]}
 * @wiki Contracts
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// The RFP Builder is part of the Contracts module — its PAGES have always checked
// this, its endpoints never did. Any logged-in analyst could read, edit or delete
// any RFP by calling the API directly. (Found by debug tool D005.)
requireModuleAccessJson('contracts');
require_once '../../includes/rfp_scope.php';
try {
    $conn = connectToDatabase();
    $search = $_GET['search'] ?? '';
    // Each RFP's company, NULL read as Default (3.5.0).
    $def = getDefaultTenantId($conn);
    [$ownCols, $ownJoin] = rfpTenantReady($conn)
        ? [", COALESCE(r.tenant_id, $def) AS tenant_id, own_t.name AS company_name", "LEFT JOIN tenants own_t ON own_t.id = COALESCE(r.tenant_id, $def)"]
        : [", NULL AS tenant_id, NULL AS company_name", ''];

    $sql = "SELECT r.id, r.name, r.status, r.contract_id, r.chosen_supplier_id,
                   r.created_by_analyst_id, r.created_datetime, r.updated_datetime,
                   a.full_name AS created_by_name,
                   c.title AS contract_title,
                   s.legal_name AS chosen_supplier_name,
                   (SELECT COUNT(*) FROM rfp_documents WHERE rfp_id = r.id) AS document_count,
                   (SELECT COUNT(*) FROM rfp_consolidated_requirements WHERE rfp_id = r.id) AS consolidated_count,
                   (SELECT COUNT(*) FROM rfp_invited_suppliers WHERE rfp_id = r.id) AS supplier_count
                   $ownCols
            FROM rfps r
            LEFT JOIN analysts a ON r.created_by_analyst_id = a.id
            LEFT JOIN contracts c ON r.contract_id = c.id
            LEFT JOIN suppliers s ON r.chosen_supplier_id = s.id
            $ownJoin";

    // The active company's RFPs, or every company the analyst can see under
    // "All companies" (3.5.0) - the same as the contracts list.
    [$scope, $params] = rfpListSql($conn, (int)$_SESSION['analyst_id'], 'r');
    $sql .= " WHERE 1=1" . $scope;
    if (!empty($search)) {
        $sql .= " AND r.name LIKE ?";
        $params[] = '%' . $search . '%';
    }

    $sql .= " ORDER BY r.updated_datetime DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $rfps = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['success' => true, 'rfps' => $rfps]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
