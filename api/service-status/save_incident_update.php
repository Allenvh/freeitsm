<?php
/**
 * API: correct an update that has already been posted (Ed, on top of #99).
 *
 * POST { id, comment?, is_internal? }
 *
 * ⚠️ Editing IN PLACE rather than posting again. Fixing a typo used to mean
 * saving the incident, which appended a second entry — so a customer read the
 * same sentence twice with the wrong version first.
 *
 * Only the wording and who can see it may change. The status and the service
 * impacts on an update are what was true at that moment; rewriting them would
 * turn the timeline into a record of what somebody wishes had happened.
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

    ServiceStatusService::editIncidentUpdate($conn, ActorContext::fromSession($conn), $id, $in);
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
