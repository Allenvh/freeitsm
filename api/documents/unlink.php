<?php
/**
 * API Endpoint: detach a document from one record.
 *
 * 🔑 THIS REMOVES THE LINK, NOT THE DOCUMENT. The same file may be attached to
 * eleven other things, and taking it off a contract must not delete it from
 * under them. Only when the LAST link goes does the document become an orphan —
 * and an orphan is then soft-deleted and its file removed, because a document
 * nothing points at is visible to nobody and would otherwise sit on disk for ever.
 *
 * You may detach from a record you can see. That is the same permission as
 * attaching to it, deliberately: anyone who can put a document on a contract can
 * take it off again.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
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

$in = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$documentId = (int) ($in['document_id'] ?? 0);
$parentType = trim((string) ($in['parent_type'] ?? ''));
$parentId   = (int) ($in['parent_id'] ?? 0);

if ($documentId <= 0 || !documentEntityDef($parentType) || $parentId <= 0) {
    echo json_encode(['success' => false, 'error' => 'A document_id, parent_type and parent_id are required.']);
    exit;
}

try {
    $conn = connectToDatabase();

    // Must be able to see the record you are detaching from...
    // documentCanWriteParent: seeing the record is not enough - Read only access to its
    // module (Change Management, Forms, Tasks) attaches and removes nothing (#181).
    if (!documentCanWriteParent($conn, $analystId, $allowed, $parentType, $parentId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You do not have access to that record.']);
        exit;
    }
    // ...and the document itself, so a guessed id cannot be used to strip a
    // document off a record by attaching-then-detaching games.
    if (!documentCanView($conn, $analystId, $allowed, $documentId)) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Not found.']);
        exit;
    }

    $conn->beginTransaction();
    try {
        $conn->prepare(
            "DELETE FROM document_links WHERE document_id = ? AND parent_type = ? AND parent_id = ?"
        )->execute([$documentId, $parentType, $parentId]);

        $remaining = (int) (function () use ($conn, $documentId) {
            $st = $conn->prepare("SELECT COUNT(*) FROM document_links WHERE document_id = ?");
            $st->execute([$documentId]);
            return $st->fetchColumn();
        })();

        $orphaned = false;
        $keyToRemove = null;
        if ($remaining === 0) {
            $st = $conn->prepare("SELECT storage_key FROM documents WHERE id = ?");
            $st->execute([$documentId]);
            $keyToRemove = $st->fetchColumn() ?: null;

            $conn->prepare("UPDATE documents SET deleted_datetime = UTC_TIMESTAMP() WHERE id = ?")
                 ->execute([$documentId]);
            $orphaned = true;
        }
        $conn->commit();
    } catch (Throwable $e) {
        if ($conn->inTransaction()) $conn->rollBack();
        throw $e;
    }

    // Only touch the disk AFTER the transaction committed. A file deleted inside
    // a transaction that then rolls back leaves a row pointing at nothing.
    if ($orphaned && $keyToRemove) {
        // ⚠️ Another document row may legitimately share the same stored file if
        // dedupe by content_hash is ever turned on. Check before unlinking.
        $st = $conn->prepare("SELECT COUNT(*) FROM documents WHERE storage_key = ? AND deleted_datetime IS NULL");
        $st->execute([$keyToRemove]);
        if ((int) $st->fetchColumn() === 0) {
            $path = documentStoragePath((string) $keyToRemove);
            if (is_file($path)) @unlink($path);
        }
    }

    // An orphan is soft-deleted, so it must leave the index too — otherwise a
    // deleted document goes on being findable by its title for ever.
    if ($orphaned) {
        try {
            require_once dirname(__DIR__, 2) . '/includes/search/documents_index.php';
            searchUnindexDocument($conn, $documentId);
        } catch (Throwable $e) { error_log('[documents/unlink] ' . $e->getMessage()); }
    }

    echo json_encode([
        'success'  => true,
        'orphaned' => $orphaned,   // true = that was its last link, so it is gone
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
