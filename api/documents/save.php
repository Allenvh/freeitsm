<?php
/**
 * API Endpoint: attach a document to something.
 *
 * Two kinds, one endpoint, because to the person using it they are one list:
 *   - file : multipart POST with `document`, stored through uploadStoreFile()
 *   - link : JSON or form POST with `external_url`, nothing stored at all
 *
 * ⚠️ THE ACCESS CHECK RUNS FIRST, BEFORE ANY FILE IS WRITTEN. Change management
 * learned this the hard way: its upload checked access AFTER move_uploaded_file(),
 * so somebody with no rights to the record could still land a file on the server
 * — the check stopped the database row, not the write.
 *
 * You may attach to a parent only if you can already SEE that parent. Which, given
 * documents inherit visibility from their parents, is also what stops attaching
 * being a way to launder a document into somewhere you can read.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/uploads.php';
require_once '../../includes/documents.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$analystId = (int) $_SESSION['analyst_id'];
$allowed   = analystAllowedModulesNow();   // the grants now, not at sign-in

// A file upload arrives as multipart; a link may arrive as either.
$body = [];
if (empty($_POST) && ($raw = file_get_contents('php://input'))) {
    $body = json_decode($raw, true) ?: [];
}
$in = array_merge($body, $_POST);

$parentType = trim((string) ($in['parent_type'] ?? ''));
$parentId   = (int) ($in['parent_id'] ?? 0);
$title       = trim((string) ($in['title'] ?? ''));
$description = trim((string) ($in['description'] ?? ''));
$externalUrl = trim((string) ($in['external_url'] ?? ''));

if ($parentType === '' || $parentId <= 0) {
    echo json_encode(['success' => false, 'error' => 'A parent_type and parent_id are required.']);
    exit;
}
if (!documentEntityDef($parentType)) {
    echo json_encode(['success' => false, 'error' => 'Unknown parent type: ' . $parentType]);
    exit;
}

try {
    $conn = connectToDatabase();

    // ---- 1. ACCESS FIRST -------------------------------------------------
    // documentCanWriteParent: seeing the record is not enough - Read only access to its
    // module (Change Management, Forms, Tasks) attaches and removes nothing (#181).
    if (!documentCanWriteParent($conn, $analystId, $allowed, $parentType, $parentId)) {
        // Deliberately the same message whether it does not exist or is not
        // yours: probing this endpoint should not map out the estate.
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You do not have access to that record.']);
        exit;
    }

    $hasFile = isset($_FILES['document']) && ($_FILES['document']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE;

    if (!$hasFile && $externalUrl === '') {
        echo json_encode(['success' => false, 'error' => 'Attach a file, or give a link to one.']);
        exit;
    }
    if ($hasFile && $externalUrl !== '') {
        echo json_encode(['success' => false, 'error' => 'Give a file or a link, not both.']);
        exit;
    }

    // ---- 2. Build the document row --------------------------------------
    if ($hasFile) {
        $stored = uploadStoreFile(
            $_FILES['document'],
            dirname(__DIR__, 2) . '/uploads/' . DOCUMENT_STORAGE_DIR,
            attachmentAllowedTypes($conn)
        );
        $doc = [
            'kind'          => DOCUMENT_KIND_FILE,
            'title'         => $title !== '' ? $title : $stored['original_name'],
            'storage_key'   => documentStorageKey($stored['stored_name']),
            'original_name' => $stored['original_name'],
            'mime_type'     => $stored['mime'],
            'size_bytes'    => $stored['size'],
            // Lets the same PDF attached to eleven assets be recognised as one file.
            'content_hash'  => @hash_file('sha256', $stored['path']) ?: null,
            'external_url'  => null,
        ];
    } else {
        // Only http(s). A javascript: or file: "link" is not a document, and this
        // string ends up in an href.
        if (!preg_match('#^https?://#i', $externalUrl)) {
            echo json_encode(['success' => false, 'error' => 'A link must start with http:// or https://']);
            exit;
        }
        if (strlen($externalUrl) > 2048) {
            echo json_encode(['success' => false, 'error' => 'That link is too long.']);
            exit;
        }
        $doc = [
            'kind'          => DOCUMENT_KIND_LINK,
            'title'         => $title !== '' ? $title : $externalUrl,
            'storage_key'   => null,
            'original_name' => null,
            'mime_type'     => null,
            'size_bytes'    => null,
            'content_hash'  => null,
            'external_url'  => $externalUrl,
        ];
    }

    // ---- 3. Write the document and its first link, together --------------
    $conn->beginTransaction();
    try {
        $ins = $conn->prepare(
            "INSERT INTO documents
                (kind, title, description, storage_key, original_name, mime_type,
                 size_bytes, content_hash, external_url, tenant_id, uploaded_by_id)
             VALUES (?,?,?,?,?,?,?,?,?,?,?)"
        );
        $ins->execute([
            $doc['kind'], mb_substr($doc['title'], 0, 255), $description !== '' ? $description : null,
            $doc['storage_key'], $doc['original_name'], $doc['mime_type'],
            $doc['size_bytes'], $doc['content_hash'], $doc['external_url'],
            getActiveTenantId($conn, $analystId) ?: null, $analystId,
        ]);
        $documentId = (int) $conn->lastInsertId();

        // documentInsertLink() also sets the Help Centre flag for an article (#177).
        documentInsertLink($conn, $documentId, $parentType, $parentId, $analystId);

        $conn->commit();
    } catch (Throwable $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        // The row failed, so the file on disk is now an orphan nothing points at.
        if ($hasFile && !empty($stored['path']) && is_file($stored['path'])) @unlink($stored['path']);
        throw $e;
    }

    // Queue the text, then index what we already have. Indexing FIRST means the
    // document is findable by title and description immediately, rather than
    // being invisible to search until a worker gets round to the file — which on
    // a bulk upload could be hours.
    try {
        require_once dirname(__DIR__, 2) . '/includes/search/documents_index.php';
        documentTextEnqueue($conn, $documentId, $doc['kind'], $doc['original_name']);
        searchIndexDocument($conn, $documentId);
        // Opportunistic: do a little extraction now, bounded, so a quiet install
        // with no cron still ends up searchable.
        documentTextDrain($conn, 1, microtime(true) + 4);
    } catch (Throwable $e) {
        error_log('[documents/save] indexing: ' . $e->getMessage());   // never fail the upload
    }

    echo json_encode(['success' => true, 'document_id' => $documentId]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
