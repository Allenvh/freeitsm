<?php
/**
 * API Endpoint: the approved templates this analyst may raise a change from (#180 §8),
 * for the change editor's "From a template" picker - each with its scope, its values and
 * which fields are completed per change. A retired or never-approved template is not offered.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/changes/templates.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('changes');

try {
    $conn = connectToDatabase();
    echo json_encode([
        'success'   => true,
        'templates' => ChangeTemplates::usable($conn, (int)$_SESSION['analyst_id']),
        'enabled'   => changeApprovalSettings($conn)['templates_enabled'],
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
