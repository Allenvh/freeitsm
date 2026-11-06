<?php
/**
 * API: Close or reopen a form collection.
 *
 * 🔴 Writes to form_collections and nothing else. What closing DOES is the
 * operator's setting, evaluated when the portal or the fill page asks — never
 * stamped onto the forms, or reopening would switch three forms back on
 * including one deliberately kept off the portal.
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
    FormsService::setCollectionClosed(
        $conn,
        ActorContext::fromSession($conn),
        (int)($input['id'] ?? 0),
        !empty($input['closed'])
    );
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
