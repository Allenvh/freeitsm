<?php
/**
 * API Endpoint: List all asset locations (flat).
 *
 * Returns the full location set as a flat list; the client builds the tree from
 * parent_id. Ordered by parent then display_order then name so siblings come
 * out in a stable, sensible order.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();
    $stmt = $conn->query(
        "SELECT id, name, parent_id, display_order
         FROM asset_locations
         ORDER BY (parent_id IS NULL) DESC, parent_id, display_order, name"
    );
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Normalise types for the client.
    foreach ($locations as &$loc) {
        $loc['id'] = (int)$loc['id'];
        $loc['parent_id'] = $loc['parent_id'] !== null ? (int)$loc['parent_id'] : null;
        $loc['display_order'] = (int)$loc['display_order'];
    }

    echo json_encode(['success' => true, 'locations' => $locations]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
