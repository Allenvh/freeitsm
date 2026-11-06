<?php
/**
 * The tracker connections a given ticket may actually be escalated to.
 *
 * Separate from list_connections.php on purpose: that one is admin-only and
 * describes connections for configuration. This one is for an ANALYST choosing
 * where to send a ticket, so it needs the tickets module rather than admin — and
 * therefore returns the bare minimum, never a hint of a credential.
 *
 * ⚠️ Filtered by the company guard, deliberately. A shared connection serves
 * everyone; a pinned one only its own company. Showing an analyst a connection
 * that integrationsEscalate() would then refuse is a worse experience than not
 * offering it — and this is the UI half of the rule, never the enforcement.
 * The enforcement is still in the service, because this list is only a
 * suggestion and the escalate endpoint cannot trust its own UI.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/integrations/integrations.php';

header('Content-Type: application/json');
requireModuleAccessJson('tickets');

$conn     = connectToDatabase();
$ticketId = isset($_GET['ticket_id']) ? (int) $_GET['ticket_id'] : 0;

if (!integrationsSchemaReady($conn)) {
    echo json_encode(['success' => true, 'connections' => []]);
    exit;
}

$entityTenant  = $ticketId > 0 ? integrationsEntityTenantId($conn, 'ticket', $ticketId) : null;
$defaultTenant = function_exists('getDefaultTenantId') ? @getDefaultTenantId($conn) : null;

$out = [];
foreach (integrationsListConnections($conn, /*activeOnly*/ true) as $c) {
    $connTenant = $c['tenant_id'] !== null ? (int) $c['tenant_id'] : null;
    if (!integrationsCompaniesCompatible($entityTenant, $connTenant, $defaultTenant)) {
        continue;
    }
    // Without credentials a connection cannot create anything, so offering it
    // would only produce a confusing failure at the tracker.
    if (empty($c['has_credentials'])) {
        continue;
    }
    $out[] = [
        'id'       => (int) $c['id'],
        'name'     => $c['name'],
        'provider' => $c['provider'],
    ];
}

echo json_encode(['success' => true, 'connections' => $out]);
