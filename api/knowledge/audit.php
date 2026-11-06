<?php
/**
 * API Endpoint: the history of one Knowledge folder or article.
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * ⚠️ THIS IS THE ONE SCREEN THAT SAYS WHO READ WHAT.
 *
 * The audit table has been written since folders shipped and displayed nowhere,
 * which made it a promise rather than a feature: the design page said "every use
 * of the administrator floor is recorded", and nothing could show that it was.
 *
 * Two gates, and both are needed for different reasons:
 *
 *   1. knowledgeCanRead()  — you cannot ask for the history of an article you
 *      cannot read. Without this, the endpoint answers "here are 40 events" for
 *      a document whose existence is meant to be hidden, and the actions and
 *      timestamps describe it well enough to be worth having.
 *
 *   2. the administrator floor — the history names PEOPLE and says when they
 *      read something. That is information about them, not only about the
 *      document, so it sits behind the same capability that governs the access
 *      list itself rather than being visible to anyone who can open the article.
 *
 * The order matters: read first, floor second, so a person WITHOUT the floor is
 * told the same thing whether the article is restricted or merely not theirs to
 * audit. Answering "you may not audit this" only for articles that exist would
 * turn this endpoint into a way of testing whether an id is real.
 * ─────────────────────────────────────────────────────────────────────────────
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/knowledge/visibility.php';
require_once '../../includes/knowledge/audit.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('knowledge');

$analystId = (int)$_SESSION['analyst_id'];
$type = $_GET['type'] ?? '';
$id   = (int)($_GET['id'] ?? 0);

if (!in_array($type, ['article', 'folder'], true) || $id <= 0) {
    echo json_encode(['success' => false, 'error' => 'Unknown object']);
    exit;
}

try {
    $conn   = connectToDatabase();
    $viewer = KnowledgeViewer::forAnalyst($conn, $analystId);

    if (!knowledgeViewerHasAdminFloor($conn, $viewer)) {
        echo json_encode(['success' => false, 'error' => 'You do not have permission to view the history.']);
        exit;
    }

    // An article must still be readable by this person. The floor makes that
    // true for anyone who gets past the check above, but asking anyway keeps the
    // rule stated in one more place rather than relying on the floor's meaning
    // never changing. `audit_override` is off: LOOKING at a history is not the
    // same as using the floor to read the document, and recording it as one
    // would fill the trail with noise about the person auditing it.
    if ($type === 'article'
        && !knowledgeCanRead($conn, $viewer, $id, ['audit_override' => false])) {
        echo json_encode(['success' => false, 'error' => 'Not found']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'entries' => knowledgeAuditHistory($conn, $type, $id, 200),
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
