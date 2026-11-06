<?php
/**
 * API Endpoint: Toggle API Key active status (revoke/activate)
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('software');
// The API keys tab's capability, as generate_apikey.php and delete_apikey.php require.
// Module access alone let any Software analyst read every key - reported privately,
// October 2026.
requireCapabilityJson(Cap::SOFTWARE_API_KEYS);

$input = json_decode(file_get_contents('php://input'), true);
$id = isset($input['id']) ? (int)$input['id'] : null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Key ID is required']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Toggle: if active=1 set to 0, if active=0 set to 1
    $sql = "UPDATE apikeys SET active = CASE WHEN active = 1 THEN 0 ELSE 1 END WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        // Fetch new status
        $stmt2 = $conn->prepare("SELECT active FROM apikeys WHERE id = ?");
        $stmt2->execute([$id]);
        $row = $stmt2->fetch(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'message' => $row['active'] ? 'API key activated' : 'API key revoked',
            'active' => (bool)$row['active']
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Key not found']);
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
