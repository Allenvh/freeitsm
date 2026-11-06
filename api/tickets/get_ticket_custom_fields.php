<?php
/**
 * API (GET): Custom ticket fields (#179), for the inbox.
 *
 *   ?ticket_id=N  - the fields an existing ticket has, with its answers.
 *   ?type_id=T    - the fields a NEW ticket of that type will ask for (the
 *                   New ticket form). &tenant_id= names the company when the
 *                   analyst is in the all-companies view, as create_ticket.php
 *                   accepts it - and is checked the same way.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/tickets.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }
requireModuleAccessJson('tickets');

try {
    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];

    if (!empty($_GET['ticket_id'])) {
        // The same scope rule as every other ticket read: not yours = not found.
        $ticket = TicketsService::loadTicket($conn, ActorContext::fromSession($conn), (int)$_GET['ticket_id']);
        echo json_encode(['success' => true, 'fields' => TicketFieldsService::forTicket($conn, $ticket)]);
        exit;
    }

    $tenantId = getActiveTenantId($conn, $analystId);
    if (isActiveTenantAll($conn) && !empty($_GET['tenant_id'])) {
        $wanted = (int)$_GET['tenant_id'];
        if (!analystCanAccessTenant($conn, $analystId, $wanted)) {
            echo json_encode(['success' => false, 'error' => 'You do not have access to that company']);
            exit;
        }
        $tenantId = $wanted;
    }
    $typeId = isset($_GET['type_id']) && $_GET['type_id'] !== '' ? (int)$_GET['type_id'] : null;
    $fields = [];
    foreach (TicketFieldsService::fieldsFor($conn, $tenantId, $typeId) as $d) {
        $fields[] = TicketFieldsService::present($d) + ['value' => null];
    }
    echo json_encode(['success' => true, 'fields' => $fields]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
