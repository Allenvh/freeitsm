<?php
/**
 * Delete an integration connection.
 *
 * ⚠️ Refuses while links still point at it. The alternative — cascading the
 * delete — would silently erase the record that ticket SD-1042 was raised in Jira
 * as OPS-412, and that reference has already been seen by people. Same principle
 * as merge, where a merged ticket is never deleted because its reference appears
 * in notifications somebody has read.
 *
 * The admin's route out is to switch the connection off, which keeps every link
 * readable but stops it being used. That is offered in the error rather than left
 * to be guessed at.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/integrations/integrations.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

$in = json_decode(file_get_contents('php://input'), true);
$id = isset($in['id']) ? (int) $in['id'] : 0;

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Bad request.']);
    exit;
}

$conn = connectToDatabase();
if (!integrationsSchemaReady($conn)) {
    echo json_encode(['success' => false, 'error' => 'Nothing to delete.']);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM integration_links WHERE connection_id = ?");
    $stmt->execute([$id]);
    $linked = (int) $stmt->fetchColumn();

    if ($linked > 0) {
        echo json_encode([
            'success' => false,
            'error'   => $linked === 1
                ? 'One ticket is still linked to an issue on this connection. Switch the connection off instead of deleting it.'
                : $linked . ' tickets are still linked to issues on this connection. Switch the connection off instead of deleting it.',
        ]);
        exit;
    }

    $del = $conn->prepare("DELETE FROM integration_connections WHERE id = ?");
    $del->execute([$id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    error_log('delete_connection: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Could not delete the connection.']);
}
