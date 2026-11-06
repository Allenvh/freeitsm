<?php
/**
 * API Endpoint: show or hide one document of a knowledge article in the
 * self-service Help Centre (GH #177).
 *
 * POST { document_id, parent_id, show }   (parent_type is always knowledge_article)
 *
 * The same two checks as attach.php and unlink.php - you must be able to see
 * the article AND the document - because showing a document to customers is
 * the same kind of act as attaching it: it widens who can read it. Editing an
 * article asks for nothing more than that (module, company, folder access), so
 * neither does this.
 *
 * Recorded in the article's history, because "who put this file in front of
 * customers" is exactly the question somebody will one day ask.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/documents.php';
require_once '../../includes/knowledge/help_centre_documents.php';
require_once '../../includes/knowledge/audit.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
$analystId = (int) $_SESSION['analyst_id'];
$allowed   = analystAllowedModulesNow();   // the grants now, not at sign-in

$in         = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$documentId = (int) ($in['document_id'] ?? 0);
$articleId  = (int) ($in['parent_id'] ?? 0);
$show       = !empty($in['show']);

if ($documentId <= 0 || $articleId <= 0) {
    echo json_encode(['success' => false, 'error' => 'A document_id and parent_id are required.']);
    exit;
}

try {
    $conn = connectToDatabase();

    if (!documentHelpCentreReady($conn)) {
        echo json_encode(['success' => false, 'error' => 'Run System → Database Verification first.']);
        exit;
    }
    if (!documentCanViewParent($conn, $analystId, $allowed, 'knowledge_article', $articleId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You do not have access to that article.']);
        exit;
    }
    if (!documentCanView($conn, $analystId, $allowed, $documentId)
        || !helpCentreSetShown($conn, $documentId, $articleId, $show)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found.']);
        exit;
    }

    try {
        $title = $conn->prepare("SELECT title FROM documents WHERE id = ?");
        $title->execute([$documentId]);
        knowledgeAuditLog($conn, 'article', $articleId, 'edit', $analystId, [
            'help_centre_document' => (string) $title->fetchColumn(),
            'shown'                => $show,
        ]);
    } catch (Throwable $e) { error_log('[documents/help_centre] audit: ' . $e->getMessage()); }

    echo json_encode(['success' => true, 'shown' => $show]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
