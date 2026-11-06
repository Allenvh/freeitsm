<?php
/**
 * API Endpoint: Save a lease outcome (3.5.0). kind 'active' = still on lease (counts
 * towards Due back); 'closed' = finished with. is_default = what an asset starts with.
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
    $id    = !empty($data['id']) ? (int)$data['id'] : null;
    $name  = mb_substr(trim((string)($data['name'] ?? '')), 0, 100);
    $kind  = ($data['kind'] ?? 'active') === 'closed' ? 'closed' : 'active';
    $order = (int)($data['display_order'] ?? 0);
    $act   = !empty($data['is_active']) ? 1 : 0;
    $def   = !empty($data['is_default']) && $kind === 'active' && $act ? 1 : 0;
    if ($name === '') throw new Exception('Name is required');
    $dup = $conn->prepare("SELECT id FROM lease_outcomes WHERE name = ? AND id <> ?");
    $dup->execute([$name, $id ?? 0]);
    if ($dup->fetchColumn()) throw new Exception('There is already an outcome with this name');
    if ($id) {
        $chk = $conn->prepare("SELECT id FROM lease_outcomes WHERE id = ?");
        $chk->execute([$id]);
        if (!$chk->fetchColumn()) throw new Exception('Outcome not found');
        $conn->prepare("UPDATE lease_outcomes SET name = ?, kind = ?, display_order = ?, is_active = ?, is_default = ? WHERE id = ?")
             ->execute([$name, $kind, $order, $act, $def, $id]);
    } else {
        $conn->prepare("INSERT INTO lease_outcomes (name, kind, display_order, is_active, is_default) VALUES (?, ?, ?, ?, ?)")
             ->execute([$name, $kind, $order, $act, $def]);
        $id = (int)$conn->lastInsertId();
    }
    // One default at a time.
    if ($def) $conn->prepare("UPDATE lease_outcomes SET is_default = 0 WHERE id <> ?")->execute([$id]);
    // A change of kind moves assets in or out of "still on lease".
    leaseSyncCalendar($conn);
    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
