<?php
/**
 * List integration connections for the settings screen.
 *
 * ⚠️ DELIBERATELY UNFILTERED BY COMPANY. integration_connections is a
 * CONNECTION-shaped table (tenant_id NULL = shared with every company, set =
 * pinned to one) — the same shape as messaging_channels and mailboxes. An admin
 * configuring routing needs to see every connection at once. Scoping this with
 * activeTenantFilter() would treat NULL as Default-owned and hide every shared
 * connection from every client company.
 *
 * ⚠️ WHICH IS EXACTLY WHY THIS MUST NOT LEAK SECRETS. A read that hands back
 * credentials needs the same care as a write, so integrationsListConnections()
 * returns has_credentials as a boolean and never the token itself — the same rule
 * api/messaging/get_channels.php follows. An unfiltered list that serialised
 * tokens would be a cross-company credential leak, not a convenience.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/integrations/integrations.php';

header('Content-Type: application/json');

$conn     = connectToDatabase();
$provider = strtolower(trim((string)($_GET['provider'] ?? '')));

$rows = integrationsListConnections($conn);

if ($provider !== '') {
    $rows = array_values(array_filter($rows, function ($r) use ($provider) {
        return $r['provider'] === $provider;
    }));
}

// Company names for display. Cheap, and it saves the page a second request.
$names = [];
if (function_exists('isMultiTenant') && isMultiTenant($conn)) {
    foreach (getAllTenants($conn) as $t) {
        $names[(int)$t['id']] = $t['name'];
    }
}
foreach ($rows as &$r) {
    $r['id']          = (int) $r['id'];
    $r['tenant_id']   = $r['tenant_id'] !== null ? (int) $r['tenant_id'] : null;
    $r['tenant_name'] = $r['tenant_id'] !== null ? ($names[$r['tenant_id']] ?? null) : null;
}

echo json_encode(['success' => true, 'connections' => $rows]);
