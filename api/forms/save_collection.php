<?php
/**
 * API: Create or rename a form collection.
 * Thin UI adapter over FormsService.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/forms.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('forms');
requireCapabilityJson(Cap::FORMS_COLLECTIONS);

try {
    $conn = connectToDatabase();
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $id = FormsService::saveCollection($conn, ActorContext::fromSession($conn), $input);
    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
