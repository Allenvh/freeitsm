<?php
/**
 * API: List configured SSO / OIDC identity providers.
 * GET - returns every provider. The client_secret is NEVER returned in
 * plaintext; only a `has_secret` flag tells the UI whether one is stored.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn = connectToDatabase();

    $stmt = $conn->query(
        "SELECT p.id, p.display_name, p.protocol, p.issuer_url, p.client_id, p.client_secret,
                p.scopes, p.enabled, p.auto_create_users, p.require_verified_email,
                p.default_modules, p.sort_order, p.tenant_id, t.name AS tenant_name
           FROM auth_providers p
           LEFT JOIN tenants t ON t.id = p.tenant_id
          ORDER BY p.sort_order, p.display_name"
    );

    $providers = [];
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
        $providers[] = [
            'id'                => (int)$r['id'],
            'display_name'      => $r['display_name'],
            'protocol'          => $r['protocol'],
            'issuer_url'        => $r['issuer_url'],
            'client_id'         => $r['client_id'],
            'scopes'            => $r['scopes'],
            'enabled'           => (int)$r['enabled'],
            'auto_create_users' => (int)$r['auto_create_users'],
            'require_verified_email' => (int)$r['require_verified_email'],
            'default_modules'   => $r['default_modules'],
            'sort_order'        => (int)$r['sort_order'],
            // Which client company owns this IdP (null = global / MSP-internal).
            'tenant_id'         => isset($r['tenant_id']) ? (int)$r['tenant_id'] : null,
            'tenant_name'       => $r['tenant_name'] ?? null,
            // Boolean flag only — the encrypted secret itself never leaves the server.
            'has_secret'        => ($r['client_secret'] !== null && $r['client_secret'] !== ''),
        ];
    }

    echo json_encode(['success' => true, 'providers' => $providers]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
