<?php
/**
 * System API: RBAC roles — list (GET) and create (POST).
 *
 * Administrators only (managing who-can-administer-what is itself an admin act),
 * enforced by admin_api_guard.php. Part of RBAC Layer 2 — see docs/design/rbac.md.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';   // loads functions.php, gates to admins
require_once '../../includes/rbac.php';

header('Content-Type: application/json');

$conn = connectToDatabase();

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Roles with a capability count and an assignment count (analysts + teams),
        // so the list can show at a glance how big and how widely-used each is.
        // managed_for: the analyst whose REST-API-managed role this is (3.5.0, PR
        // #174) - shown read-only. NULL before Database Verification adds the column.
        $managed = "NULL AS managed_for_analyst_id, NULL AS managed_for_name";
        try {
            $conn->query("SELECT managed_for_analyst_id FROM rbac_roles LIMIT 0");
            $managed = "r.managed_for_analyst_id, (SELECT a.full_name FROM analysts a WHERE a.id = r.managed_for_analyst_id) AS managed_for_name";
        } catch (Throwable $e) {}
        $sql = "SELECT r.id, r.name, r.description, r.is_active, $managed,
                       (SELECT COUNT(*) FROM rbac_role_capabilities rc WHERE rc.role_id = r.id) AS capability_count,
                       (SELECT COUNT(*) FROM rbac_analyst_roles ar WHERE ar.role_id = r.id) AS analyst_count,
                       (SELECT COUNT(*) FROM rbac_team_roles tr WHERE tr.role_id = r.id) AS team_count
                FROM rbac_roles r
                ORDER BY r.name";
        echo json_encode(['success' => true, 'data' => $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC)]);
        exit;
    }

    // POST — create a role. Capabilities/assignments are set via role.php afterwards.
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $name  = trim($input['name'] ?? '');
    if ($name === '') throw new Exception('A role name is required.');

    $stmt = $conn->prepare("INSERT INTO rbac_roles (name, description, is_active, created_by_id, created_datetime, updated_datetime)
                            VALUES (?, ?, 1, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())");
    $stmt->execute([$name, trim($input['description'] ?? ''), (int)$_SESSION['analyst_id']]);

    echo json_encode(['success' => true, 'id' => (int)$conn->lastInsertId()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
