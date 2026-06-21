<?php
/**
 * API Endpoint: Get users list
 * Returns users with optional search filtering
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

// Get search parameter
$search = $_GET['search'] ?? '';

try {
    $conn = connectToDatabase();

    // Multi-tenancy: the per-user ticket count is scoped to the active company
    // (no-op at N=1), so it doesn't reveal a requester's activity in other
    // companies (§9). The placeholder sits in the SELECT subquery, so its param
    // must lead the bound list.
    list($ttSql, $ttParams) = ticketTenantFilter($conn, (int)$_SESSION['analyst_id'], 't');

    // Build query with optional search
    $sql = "SELECT
                u.id,
                u.email,
                u.display_name,
                u.preferred_name,
                u.created_at,
                (SELECT COUNT(*) FROM tickets t WHERE t.user_id = u.id{$ttSql}) as ticket_count
            FROM users u";

    $params = $ttParams;

    if (!empty($search)) {
        $sql .= " WHERE u.display_name LIKE ? OR u.email LIKE ?";
        $searchParam = '%' . $search . '%';
        $params = array_merge($params, [$searchParam, $searchParam]);
    }

    $sql .= " ORDER BY u.display_name ASC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'users' => $users
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

?>
