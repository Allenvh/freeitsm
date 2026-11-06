<?php
/**
 * API Endpoint: one text revision of a change, WITH its full content, and the id of
 * the revision before it for the same field.
 *
 *   GET ?change_id=N&revision_id=R
 *
 * The content is the editor's HTML as saved. TRAP: it is author-written and shown to
 * somebody else, so the browser must render it through assets/js/safe-html.js, never
 * straight into innerHTML (change-revisions.js does).
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

$changeId   = (int)($_GET['change_id'] ?? 0);
$revisionId = (int)($_GET['revision_id'] ?? 0);

if (!$changeId || !$revisionId) {
    echo json_encode(['success' => false, 'error' => 'Change ID and revision ID required']);
    exit;
}

try {
    $conn = connectToDatabase();

    if (!analystCanAccessChange($conn, (int)$_SESSION['analyst_id'], $changeId)) {
        echo json_encode(['success' => false, 'error' => 'Change not found']);
        exit;
    }

    $rev = ChangeRevisions::get($conn, $changeId, $revisionId);
    if ($rev === null) {
        echo json_encode(['success' => false, 'error' => 'Revision not found']);
        exit;
    }
    echo json_encode(['success' => true, 'revision' => $rev]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
