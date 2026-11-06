<?php
/**
 * API: every leaf form with the collection it is currently in.
 *
 * The picker shows where a form already lives, because a form belongs to at
 * most one collection — so ticking one here TAKES it from somewhere else, and
 * nobody should discover that afterwards.
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
    echo json_encode(['success' => true, 'forms' => FormsService::formsForPicker($conn)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
