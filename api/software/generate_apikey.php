<?php
/**
 * API Endpoint: Generate new API Key
 * Creates a new random API key, bound to the current analyst
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
requireCapabilityJson(Cap::SOFTWARE_API_KEYS);   // settings tab — see docs/design/rbac.md

try {
    $conn = connectToDatabase();

    $input = json_decode(file_get_contents('php://input'), true);
    $label = isset($input['label']) ? trim($input['label']) : null;
    if ($label === '') $label = null;

    // Generate a secure random 40-character hex key
    $apikey = bin2hex(random_bytes(20));

    $sql = "INSERT INTO apikeys (apikey, analyst_id, label) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$apikey, $_SESSION['analyst_id'], $label]);
    $newId = $conn->lastInsertId();

    echo json_encode([
        'success' => true,
        'message' => 'API key generated',
        'id' => $newId,
        'apikey' => $apikey
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
