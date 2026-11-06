<?php
/**
 * API: set which forms belong to a collection, from the collection's side.
 *
 * Writes `forms.collection_id` only. Submissions already made keep the
 * collection they were filed under — unlinking a form here must never rewrite
 * history, which is the whole reason the stamp is a separate column.
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
    $result = FormsService::setCollectionForms(
        $conn,
        ActorContext::fromSession($conn),
        (int)($input['collection_id'] ?? 0),
        is_array($input['form_ids'] ?? null) ? $input['form_ids'] : []
    );
    echo json_encode(['success' => true] + $result);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
