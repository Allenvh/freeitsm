<?php
/**
 * API (POST): Creates or changes a custom ticket field (#179). Thin adapter over TicketFieldsService::saveField().
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/services/ticket_fields.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_CUSTOM_FIELDS);   // settings tab - see docs/design/rbac.md

try {
    $conn = connectToDatabase();
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $res  = TicketFieldsService::saveField($conn, ActorContext::fromSession($conn), $data);
    echo json_encode(['success' => true] + $res);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
