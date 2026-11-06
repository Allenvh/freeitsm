<?php
/**
 * API Endpoint: Delete a lease outcome (3.5.0). Refused while any leased asset has
 * it - switch it off instead, so the record of what happened stays.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/leases.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');
requireCapabilityJson(Cap::ASSETS_LEASING);   // settings write — see docs/design/rbac.md

try {
    $conn = connectToDatabase();
    if (!leaseReady($conn)) throw new Exception('Run Database Verification (System -> Database) to add leases.');
    // One list for every company, so - like shared asset locations - only
    // someone who can already see every company may change it.
    if (!analystHasAllTenantAccess($conn, (int)$_SESSION['analyst_id'])) {
        throw new Exception('Lease outcomes are shared by every company. Only someone with access to all companies can change them.');
    }
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $id = (int)($data['id'] ?? 0);
    $n = $conn->prepare("SELECT COUNT(*) FROM lease_assets WHERE outcome_id = ?");
    $n->execute([$id]);
    if (($used = (int)$n->fetchColumn()) > 0) {
        throw new Exception("$used leased asset" . ($used === 1 ? ' has' : 's have') . " this outcome. Switch it off instead - it stays on those and is not offered for new ones.");
    }
    $conn->prepare("DELETE FROM lease_outcomes WHERE id = ?")->execute([$id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
