<?php
/**
 * API: remove one update from an incident's timeline (Ed, on top of #99).
 * POST { id }
 *
 * The incident and every other update survive it. Worth having alongside
 * editing: a correction fixes wording, but an update posted to the wrong
 * incident, or published by mistake, needs to go rather than be reworded.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/service_status.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('service-status');

try {
    $in = json_decode(file_get_contents('php://input'), true);
    if (!is_array($in)) {
        throw new Exception('Invalid JSON');
    }
    $id = (int)($in['id'] ?? 0);
    if ($id <= 0) {
        throw new Exception('id is required');
    }

    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    ServiceStatusService::deleteIncidentUpdate($conn, ActorContext::fromSession($conn), $id);
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
