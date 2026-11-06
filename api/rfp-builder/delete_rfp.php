<?php
/**
 * API: delete an RFP and everything under it.
 *
 * Child rows go by ON DELETE CASCADE; the uploaded document files are deleted from disk
 * afterwards. rfpGate() answers "not found" for an RFP in a company the analyst cannot
 * reach.
 *
 * @input  id  the RFP (JSON body)
 * @output {success}
 * @wiki Contracts
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

// The RFP Builder is part of the Contracts module — its PAGES have always checked
// this, its endpoints never did. Any logged-in analyst could read, edit or delete
// any RFP by calling the API directly. (Found by debug tool D005.)
requireModuleAccessJson('contracts');
// The RFP's company (3.5.0): one in a company this analyst cannot reach is "not found".
require_once '../../includes/rfp_scope.php';
rfpGate('rfp', 'id');
try {
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id'] ?? null;
    if (!$id) {
        throw new Exception('ID is required');
    }

    $conn = connectToDatabase();

    // Collect file paths for any uploaded documents so we can clean them up after the cascade delete.
    $stmt = $conn->prepare("SELECT file_path FROM rfp_documents WHERE rfp_id = ?");
    $stmt->execute([$id]);
    $paths = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Cascade is handled by FK ON DELETE CASCADE on every child table.
    $del = $conn->prepare("DELETE FROM rfps WHERE id = ?");
    $del->execute([$id]);

    foreach ($paths as $p) {
        if ($p && file_exists($p)) {
            @unlink($p);
        }
    }

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
