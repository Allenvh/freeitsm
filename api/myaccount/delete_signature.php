<?php
/**
 * API: delete one of this analyst's own signatures.
 * POST { id }
 *
 * ⚠️ The DELETE is scoped by analyst_id from the session as well as by id, so the
 * worst an analyst can do with somebody else's id is delete nothing.
 *
 * Deleting the default does NOT leave the analyst with no default:
 * defaultSignatureForAnalyst() falls back to the first remaining one. Promoting a
 * replacement here as well would be a second rule saying the same thing, and the two
 * would eventually disagree.
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

try {
    $data = json_decode(file_get_contents('php://input'), true);
    $id   = (int)($data['id'] ?? 0);
    if (!$id) {
        throw new Exception('No signature was named.');
    }

    $conn = connectToDatabase();
    $stmt = $conn->prepare("DELETE FROM analyst_signatures WHERE id = ? AND analyst_id = ?");
    $stmt->execute([$id, (int)$_SESSION['analyst_id']]);

    echo json_encode(['success' => true, 'deleted' => $stmt->rowCount() > 0]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
