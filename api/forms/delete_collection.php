<?php
/**
 * API: Delete a form collection.
 *
 * Refused while anything is stamped into it — such a collection can be CLOSED
 * but not deleted, or the audit trail the stamp exists for goes with it. The
 * database enforces the same thing, but a constraint error is not an
 * explanation, so the service says it in a sentence first.
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
    FormsService::deleteCollection($conn, ActorContext::fromSession($conn), (int)($input['id'] ?? 0));
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
