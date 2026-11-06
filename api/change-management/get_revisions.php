<?php
/**
 * API Endpoint: the text revisions of a change, WITHOUT their content (cheap to list).
 *
 *   GET ?change_id=N[&field=test_plan]
 *
 * Same gate as every by-id read of a change: module access, then the change's company
 * (analystCanAccessChange - an out-of-scope change answers exactly like a missing one).
 * The full text of one revision comes from get_revision.php. See
 * includes/changes/revisions.php.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/changes/revisions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('changes');

$changeId = (int)($_GET['change_id'] ?? 0);
$field    = isset($_GET['field']) && $_GET['field'] !== '' ? (string)$_GET['field'] : null;

if (!$changeId) {
    echo json_encode(['success' => false, 'error' => 'Change ID required']);
    exit;
}
if ($field !== null && !isset(ChangeRevisions::FIELDS[$field]) && $field !== 'comment') {
    echo json_encode(['success' => false, 'error' => 'Unknown field']);
    exit;
}

try {
    $conn = connectToDatabase();

    if (!analystCanAccessChange($conn, (int)$_SESSION['analyst_id'], $changeId)) {
        echo json_encode(['success' => false, 'error' => 'Change not found']);
        exit;
    }

    echo json_encode(['success' => true, 'revisions' => ChangeRevisions::listFor($conn, $changeId, $field)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
