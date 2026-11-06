<?php
/**
 * API Endpoint: Delete an analyst.
 *
 * Kept for anything that still posts { id } here; the screen now uses
 * analyst_leaving.php, which can also move their work and offer Deactivate or
 * Anonymise. Both go through analystDelete() (includes/analyst_lifecycle.php),
 * which removes their personal settings with them and refuses - in words, not a
 * foreign-key error (GH #178) - when their name is on history or they still
 * hold open work.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';
require_once '../../includes/analyst_lifecycle.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request data']);
    exit;
}

try {
    $conn = connectToDatabase();
    analystDelete($conn, ActorContext::fromSession($conn), (int) $data['id']);
    echo json_encode(['success' => true, 'message' => 'Analyst deleted successfully']);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
