<?php
/**
 * API Endpoint: what is this document attached to?
 *
 * GET ?id=7
 *
 * Powers the ⓘ button. Answers only for a document the caller may see, and lists
 * only the parents they may see — so it can never be used to enumerate records
 * behind a document id, which would turn an information button into a probe.
 *
 * ⚠️ It also reports how many parents were hidden as a COUNT ONLY. Saying "and 2
 * others" is a judgement call: it tells somebody the document is more widely
 * attached than they can see, which matters when they are deciding whether to
 * attach it somewhere new — and it names nothing.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/documents.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$analystId  = (int) $_SESSION['analyst_id'];
$allowed    = analystAllowedModulesNow();   // the grants now, not at sign-in
$documentId = (int) ($_GET['id'] ?? 0);

if ($documentId <= 0) {
    echo json_encode(['success' => false, 'error' => 'A document id is required.']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Same boundary as the download: you must be able to see the document itself.
    if (!documentCanView($conn, $analystId, $allowed, $documentId)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found.']);
        exit;
    }

    $st = $conn->prepare(
        "SELECT d.id, d.kind, d.title, d.description, d.original_name, d.mime_type,
                d.size_bytes, d.external_url, d.created_datetime,
                a.full_name AS uploaded_by_name,
                t.status AS index_status, t.chars AS index_chars
           FROM documents d
      LEFT JOIN analysts a      ON a.id = d.uploaded_by_id
      LEFT JOIN document_text t ON t.document_id = d.id
          WHERE d.id = ? AND d.deleted_datetime IS NULL"
    );
    $st->execute([$documentId]);
    $doc = $st->fetch(PDO::FETCH_ASSOC);
    if (!$doc) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found.']);
        exit;
    }

    $visible = documentVisibleParents($conn, $analystId, $allowed, $documentId);

    $totalSt = $conn->prepare("SELECT COUNT(*) FROM document_links WHERE document_id = ?");
    $totalSt->execute([$documentId]);
    $total = (int) $totalSt->fetchColumn();

    echo json_encode([
        'success'  => true,
        'document' => [
            'id'               => (int) $doc['id'],
            'kind'             => $doc['kind'],
            'title'            => $doc['title'],
            'description'      => $doc['description'],
            'original_name'    => $doc['original_name'],
            'mime_type'        => $doc['mime_type'],
            'size_bytes'       => $doc['size_bytes'] !== null ? (int) $doc['size_bytes'] : null,
            'external_url'     => $doc['external_url'],
            'created_datetime' => $doc['created_datetime'],
            'uploaded_by_name' => $doc['uploaded_by_name'],
            // So somebody can tell "not searchable yet" from "nothing to read".
            'index_status'     => $doc['index_status'],
            'index_chars'      => $doc['index_chars'] !== null ? (int) $doc['index_chars'] : null,
        ],
        'links'        => $visible,
        'hidden_count' => max(0, $total - count($visible)),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
