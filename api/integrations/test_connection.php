<?php
/**
 * Test an integration connection's credentials with a read-only call.
 *
 * Works on an UNSAVED form as well as a stored connection, so an admin can prove
 * the token before committing it. When an id is supplied, whatever the form left
 * blank falls back to what is stored — the page never receives the stored secret,
 * so without that merge, testing an existing connection would always fail.
 *
 * ⚠️ A successful test is also where account_identity is captured — who our token
 * authenticates as. Nothing reads it until comment sync, but it is half of echo
 * suppression (an inbound event authored by this identity is our own write coming
 * back), and back-filling it for links that already exist is miserable. The same
 * call settles the Cloud/Data Center flavour, so both are persisted here.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php';
require_once '../../includes/encryption.php';
require_once '../../includes/ssl.php';
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

$conn     = connectToDatabase();
$id       = isset($in['id']) && $in['id'] !== null && $in['id'] !== '' ? (int) $in['id'] : null;
$provider = strtolower(trim((string)($in['provider'] ?? '')));
$baseUrl  = trim((string)($in['base_url'] ?? ''));
$creds    = is_array($in['credentials'] ?? null) ? $in['credentials'] : [];

$meta = integrationsProviderMeta($provider);
if (!$meta) {
    echo json_encode(['success' => false, 'error' => 'Unknown provider.']);
    exit;
}
// Non-tracker providers (Slack) have their own test on their own page — this one
// would try to reach a tracker API that does not exist.
if (($meta['kind'] ?? 'tracker') !== 'tracker') {
    echo json_encode(['success' => false, 'error' => ucfirst($provider) . ' is tested from its own page.']);
    exit;
}
if ($baseUrl !== '' && !preg_match('#^https?://#i', $baseUrl)) {
    $baseUrl = 'https://' . $baseUrl;
}

$stored = $id ? integrationsLoadConnection($conn, $id) : null;
if ($stored) {
    $creds   = array_merge((array)$stored['credentials'], $creds);
    if ($baseUrl === '') $baseUrl = (string) $stored['base_url'];
}
if ($baseUrl === '') {
    echo json_encode(['success' => false, 'error' => 'Enter the site URL first.']);
    exit;
}

try {
    $providerObj = integrationsProviderFor([
        'provider'    => $provider,
        'base_url'    => $baseUrl,
        'credentials' => $creds,
    ]);
    $result = $providerObj->testConnection();

    // Persist what the test discovered, but only for a connection that exists.
    // An unsaved form has nowhere to put it and will be saved momentarily anyway.
    if ($stored) {
        $creds['flavour'] = $result['flavour'] ?? ($creds['flavour'] ?? null);
        $upd = $conn->prepare(
            "UPDATE integration_connections SET account_identity = ?, credentials = ? WHERE id = ?"
        );
        $upd->execute([
            $result['account_identity'] ?? null,
            encryptValue(json_encode(array_filter($creds, function ($v) { return $v !== null; }))),
            $id,
        ]);
    }

    // Hand the discovered facts back even when there was nothing to save them
    // to. Testing from the ADD dialog is the most natural flow — you prove the
    // token before committing it — and without this the identity discovered
    // there would be thrown away, leaving a saved connection with none.
    // The page carries these into the save payload.
    echo json_encode([
        'success'          => true,
        'detail'           => $result['detail'] ?? 'Connected.',
        'account_identity' => $result['account_identity'] ?? null,
        'flavour'          => $result['flavour'] ?? null,
    ]);
} catch (Exception $e) {
    // The provider's own message is the useful part ("Epic Link is required",
    // "Jira rejected the credentials") — pass it through rather than flattening
    // every failure into "test failed".
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
