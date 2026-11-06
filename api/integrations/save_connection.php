<?php
/**
 * Create or update an integration connection.
 *
 * ⚠️ Credentials are ENCRYPTED AT REST (encryptValue → the ENC: prefix), the same
 * as messaging_channels.credentials.
 *
 * ⚠️ An empty credentials object on an EDIT means "keep what is stored", not
 * "clear it". The settings page never receives the stored secret (see
 * list_connections.php), so it cannot send it back — without this rule, every
 * edit of a connection's name would silently wipe its token. The page says so in
 * the form; this is the half that enforces it.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/encryption.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/integrations/integrations.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Bad request.']);
    exit;
}

$conn = connectToDatabase();

if (!integrationsSchemaReady($conn)) {
    echo json_encode(['success' => false, 'error' => 'Run Database Verification first — the integration tables do not exist yet.']);
    exit;
}

$id       = isset($in['id']) && $in['id'] !== null && $in['id'] !== '' ? (int) $in['id'] : null;
$provider = strtolower(trim((string)($in['provider'] ?? '')));
$name     = trim((string)($in['name'] ?? ''));
$baseUrl  = trim((string)($in['base_url'] ?? ''));
$creds    = is_array($in['credentials'] ?? null) ? $in['credentials'] : [];
$isActive = !empty($in['is_active']) ? 1 : 0;
// Off unless explicitly switched on. Inbound WRITES to tickets, so a
// half-finished setup must not start posting notes — the same "nothing is
// processed until you say so" rule the messaging webhooks use.
$inbound  = !empty($in['inbound_enabled']) ? 1 : 0;
// Defaults ON for a NEW connection (the screenshot is usually the bug report),
// but an explicit false from the form must be honoured — hence array_key_exists
// rather than a truthy check, which would silently re-enable it on every save.
$sendAtt  = array_key_exists('send_attachments', $in) ? (!empty($in['send_attachments']) ? 1 : 0) : 1;
$tenantId = (isset($in['tenant_id']) && $in['tenant_id'] !== null && $in['tenant_id'] !== '')
                ? (int) $in['tenant_id'] : null;

// Facts a preceding Test discovered on this form. Carried through rather than
// re-fetched: saving must not depend on the tracker being reachable, and the
// answer is already known. Both are optional — a save without a test simply
// leaves them for the next successful test to fill in.
$identity = isset($in['account_identity']) && $in['account_identity'] !== ''
                ? (string) $in['account_identity'] : null;
$flavour  = isset($in['flavour']) && $in['flavour'] !== ''
                ? (string) $in['flavour'] : null;

$meta = integrationsProviderMeta($provider);
if (!$meta) {
    echo json_encode(['success' => false, 'error' => 'Unknown provider.']);
    exit;
}
// The registry also lists providers that are NOT issue trackers (Slack is a
// messaging channel and keeps its connections in `messaging_channels`). Storing
// one here would create a tracker connection that can never be dispatched —
// integrationsProviderFor() would throw the moment anything used it.
if (($meta['kind'] ?? 'tracker') !== 'tracker') {
    echo json_encode(['success' => false, 'error' => ucfirst($provider) . ' is not an issue tracker — it is set up on its own page.']);
    exit;
}
if ($name === '')    { echo json_encode(['success' => false, 'error' => 'Give the connection a name.']); exit; }
if ($baseUrl === '') { echo json_encode(['success' => false, 'error' => 'Enter the site URL.']); exit; }

// A URL typed without a scheme is the most likely input mistake and produces a
// baffling cURL error later, so fix it here rather than at 3am in a cron log.
if (!preg_match('#^https?://#i', $baseUrl)) {
    $baseUrl = 'https://' . $baseUrl;
}
if (!filter_var($baseUrl, FILTER_VALIDATE_URL)) {
    echo json_encode(['success' => false, 'error' => 'That does not look like a valid URL.']);
    exit;
}

// ⚠️ You may only pin a connection to a company you can actually reach. This is
// the write gate that the deliberately-unfiltered read list relies on.
if ($tenantId !== null && function_exists('analystCanAssignTenant')) {
    if (!analystCanAssignTenant($conn, (int)$_SESSION['analyst_id'], $tenantId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'You cannot assign a connection to that company.']);
        exit;
    }
}

try {
    if ($id) {
        $existing = integrationsLoadConnection($conn, $id);
        if (!$existing) {
            echo json_encode(['success' => false, 'error' => 'That connection no longer exists.']);
            exit;
        }
        // Merge over what is stored, so a blank box keeps the existing secret.
        $merged = array_merge((array)$existing['credentials'], $creds);
        // The flavour is a sniffed fact about the site, not a credential the admin
        // typed — carry it across an edit rather than making the next call re-guess.
        if ($flavour !== null) {
            $merged['flavour'] = $flavour;
        } elseif (isset($existing['credentials']['flavour']) && !isset($merged['flavour'])) {
            $merged['flavour'] = $existing['credentials']['flavour'];
        }
        $blob = encryptValue(json_encode($merged));

        $stmt = $conn->prepare(
            "UPDATE integration_connections
             SET name = ?, base_url = ?, credentials = ?, tenant_id = ?, is_active = ?,
                 inbound_enabled = ?, send_attachments = ?, account_identity = COALESCE(?, account_identity)
             WHERE id = ?"
        );
        $stmt->execute([$name, $baseUrl, $blob, $tenantId, $isActive, $inbound, $sendAtt, $identity, $id]);
    } else {
        if ($flavour !== null) $creds['flavour'] = $flavour;
        $blob = encryptValue(json_encode($creds));
        $stmt = $conn->prepare(
            "INSERT INTO integration_connections
                (name, provider, base_url, auth_type, credentials, tenant_id, is_active,
                 inbound_enabled, send_attachments, created_by, account_identity)
             VALUES (?, ?, ?, 'api_token', ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $provider, $baseUrl, $blob, $tenantId, $isActive, $inbound, $sendAtt,
                        $_SESSION['analyst_id'] ?? null, $identity]);
        $id = (int) $conn->lastInsertId();
    }
    echo json_encode(['success' => true, 'id' => $id]);
} catch (Exception $e) {
    error_log('save_connection: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Could not save the connection.']);
}
