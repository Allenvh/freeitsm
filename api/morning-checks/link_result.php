<?php
/**
 * API: record that a ticket or task was raised from a morning check (#64).
 *
 * A breadcrumb, written after the ticket or task already exists. It is
 * deliberately not part of that creation: the follow-up is the valuable thing,
 * and losing the note of where it came from must never look like the ticket
 * failed to be raised.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('morning-checks');

try {
    $conn = connectToDatabase();
    $in   = json_decode(file_get_contents('php://input'), true) ?: [];

    $resultId = (int)($in['result_id'] ?? 0);
    $entityId = (int)($in['entity_id'] ?? 0);
    // Whitelisted: this value is stored and later drives which module a link
    // points at, so it is a choice from a fixed set rather than a free string.
    $type     = (string)($in['entity_type'] ?? '');
    if (!in_array($type, ['ticket', 'task'], true) || $resultId <= 0 || $entityId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid link.']);
        exit;
    }

    $conn->prepare(
        "INSERT INTO morningChecks_ResultLinks (ResultID, EntityType, EntityID, EntityRef, CreatedByID, CreatedDate)
         VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP())"
    )->execute([
        $resultId,
        $type,
        $entityId,
        isset($in['entity_ref']) ? substr((string)$in['entity_ref'], 0, 100) : null,
        (int)$_SESSION['analyst_id'],
    ]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // The link is a nicety; the caller must not surface this as a failure.
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
