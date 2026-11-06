<?php
/**
 * API (GET): Lists the custom ticket fields for Tickets -> Settings -> Custom fields (#179), with the ticket types they can be linked to.
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
    $fields = TicketFieldsService::listForSettings($conn, (int)$_SESSION['analyst_id']);
    echo json_encode([
        'success' => true,
        'ready'   => TicketFieldsService::schemaReady($conn),
        'fields'  => $fields,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
