<?php
/**
 * API Endpoint: attach an EXISTING document to a record.
 *
 * POST { document_id, parent_type, parent_id }
 *
 * The counterpart to save.php, which creates a new document. This one adds a
 * link to one that already exists — one warranty on eleven laptops, stored once.
 *
 * ⚠️ BOTH ENDS ARE CHECKED, and the first is the one that is easy to miss. You
 * must be able to see the DOCUMENT already, not merely the record you are
 * attaching it to. Without that check, anyone could attach a document they have
 * no access to onto a record they do — and then read it, because visibility is
 * inherited from the parent. Two clicks, and the permission model is inverted.
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

$analystId = (int) $_SESSION['analyst_id'];
$allowed   = analystAllowedModulesNow();   // the grants now, not at sign-in

$in         = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$documentId = (int) ($in['document_id'] ?? 0);
$parentType = trim((string) ($in['parent_type'] ?? ''));
$parentId   = (int) ($in['parent_id'] ?? 0);

if ($documentId <= 0 || !documentEntityDef($parentType) || $parentId <= 0) {
    echo json_encode(['success' => false, 'error' => 'A document_id, parent_type and parent_id are required.']);
    exit;
}

try {
    $conn = connectToDatabase();

    // 1. The record you are attaching TO.
    // documentCanWriteParent: seeing the record is not enough - Read only access to its
    // module (Change Management, Forms, Tasks) attaches and removes nothing (#181).
    if (!documentCanWriteParent($conn, $analystId, $allowed, $parentType, $parentId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You do not have access to that record.']);
        exit;
    }
    // 2. The document itself. Same refusal for "no such document" and "not
    //    yours", so this cannot be used to probe which ids exist.
    if (!documentCanView($conn, $analystId, $allowed, $documentId)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found.']);
        exit;
    }

    // INSERT IGNORE against the unique key: attaching twice is a no-op, not an
    // error somebody has to understand.
    // documentInsertLink() also sets the Help Centre flag for an article (#177).
    documentInsertLink($conn, $documentId, $parentType, $parentId, $analystId, true);

    // The corpus row carries no parent, but its VISIBILITY just changed — and the
    // search clause resolves that live, so nothing needs reindexing. Reindexed
    // anyway to keep source_datetime and title honest if it was edited elsewhere.
    try {
        require_once dirname(__DIR__, 2) . '/includes/search/documents_index.php';
        searchIndexDocument($conn, $documentId);
    } catch (Throwable $e) { error_log('[documents/attach] ' . $e->getMessage()); }

    echo json_encode(['success' => true, 'document_id' => $documentId]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
