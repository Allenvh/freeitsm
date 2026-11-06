<?php
/**
 * API: Knowledge — save the assistant's draft.
 *
 * POST { title, body_html, ticket_id?, cluster_id? }
 *
 * Creates an UNPUBLISHED article. That is the point: the assistant proposes, a
 * human decides. Nothing an AI wrote reaches a reader — least of all a
 * customer-facing portal — until somebody has read it and pressed publish.
 *
 * Goes through KnowledgeService rather than its own INSERT so drafts get the
 * same validation, company scoping, audience default and embedding regeneration
 * as every other article. The one thing it asks for that no other caller does is
 * is_published => false.
 */

session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/encryption.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/knowledge.php';
require_once '../../includes/knowledge/writeup_ai.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('knowledge');

$analystId = (int)$_SESSION['analyst_id'];
$input     = json_decode(file_get_contents('php://input'), true) ?: [];
$title     = trim((string)($input['title'] ?? ''));
$bodyHtml  = (string)($input['body_html'] ?? '');
$ticketId  = (int)($input['ticket_id'] ?? 0);
$clusterId = (int)($input['cluster_id'] ?? 0);

if ($title === '') {
    echo json_encode(['success' => false, 'error' => 'The draft needs a title']);
    exit;
}
if (trim(strip_tags($bodyHtml)) === '') {
    echo json_encode(['success' => false, 'error' => 'The draft is empty']);
    exit;
}

try {
    $conn = connectToDatabase();

    // A draft written from a ticket inherits that ticket's company, so an MSP
    // does not end up with one client's article shared across every client.
    // Ticket tenancy, not knowledge tenancy — and only after checking the
    // analyst may read the ticket at all.
    $tenantId = null;
    if ($ticketId > 0) {
        if (!analystCanAccessTicket($conn, $analystId, $ticketId)) {
            echo json_encode(['success' => false, 'error' => 'Ticket not found']);
            exit;
        }
        $st = $conn->prepare("SELECT tenant_id FROM tickets WHERE id = ?");
        $st->execute([$ticketId]);
        $raw = $st->fetchColumn();
        $tenantId = ($raw === false || $raw === null) ? null : (int)$raw;
    }

    $res = KnowledgeService::saveArticle($conn, ActorContext::fromSession($conn), [
        'title'        => mb_substr($title, 0, 255),
        'body_html'    => $bodyHtml,
        'is_published' => false,
        'tenant_id'    => $tenantId,
    ]);
    $articleId = (int)$res['id'];

    // Close the loop: the cluster now has an article, so the assistant stops
    // reporting it and starts pointing at it instead.
    if ($clusterId > 0 && writeupSchemaReady($conn)) {
        [$tSql, $tArgs] = activeTenantFilter($conn, $analystId, 'c');
        $chk = $conn->prepare("SELECT c.id FROM knowledge_gap_clusters c WHERE c.id = ? {$tSql}");
        $chk->execute(array_merge([$clusterId], $tArgs));
        if ($chk->fetchColumn()) {
            $conn->prepare("UPDATE knowledge_gap_clusters SET status='written', article_id=? WHERE id=?")
                 ->execute([$articleId, $clusterId]);
        }
    }

    echo json_encode([
        'success'    => true,
        'article_id' => $articleId,
        'published'  => false,
        'message'    => 'Saved as a draft. Review it, then publish when you are happy.',
    ]);

} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
