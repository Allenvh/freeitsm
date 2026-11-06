<?php
/**
 * API Endpoint: Delete a rota entry
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireModuleOnJson(connectToDatabase(), 'rota');   // GH #217: not while the duty rota is switched off

$input = json_decode(file_get_contents('php://input'), true);
$id = !empty($input['id']) ? (int)$input['id'] : null;

if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Entry ID is required']);
    exit;
}

try {
    $conn = connectToDatabase();

    $sql = "DELETE FROM ticket_rota_entries WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
