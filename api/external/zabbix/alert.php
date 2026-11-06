<?php
/**
 * External endpoint: an event from Zabbix (its "FreeITSM" webhook media type).
 *
 * POST, JSON body:
 *   { "token": "...", "problem_id": "{EVENT.ID}", "event_value": "{EVENT.VALUE}",
 *     "severity": "{EVENT.NSEVERITY}", "host": "{HOST.NAME}", "trigger": "{EVENT.NAME}",
 *     "message": "{ALERT.MESSAGE}", "url": "..." }
 * The token may instead be sent as "Authorization: Bearer <token>".
 *
 * No FreeITSM sign-in: the token, made on System → Integrations → Zabbix, is the
 * credential. This path is on CSRF_EXEMPT_PATHS (api/external/) because Zabbix is
 * cross-origin by design and never carries a login cookie.
 *
 * What each event does: includes/integrations/zabbix.php.
 */
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/integrations/zabbix.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['success' => false, 'error' => 'POST a JSON body.']);
    exit;
}

// A Zabbix event is small. Refuse anything that is not, before parsing it.
$raw = (string)file_get_contents('php://input', false, null, 0, 65536 + 1);
if (strlen($raw) > 65536) {
    http_response_code(413);
    echo json_encode(['success' => false, 'error' => 'Too large.']);
    exit;
}
$p = json_decode($raw, true);
if (!is_array($p)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'The body must be JSON.']);
    exit;
}

try {
    $conn = connectToDatabase();
    if (!zabbixReady($conn)) {
        http_response_code(503);
        echo json_encode(['success' => false, 'error' => 'Run Database Verification first.']);
        exit;
    }
    $expected = zabbixToken($conn);
    $given = (string)($p['token'] ?? '');
    if ($given === '' && preg_match('/^Bearer\s+(\S+)$/i', (string)($_SERVER['HTTP_AUTHORIZATION'] ?? ''), $m)) $given = $m[1];
    // No token made yet = the integration is off; never compare against ''.
    if ($expected === '' || $given === '' || !hash_equals($expected, $given)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Bad token.']);
        exit;
    }

    $r = zabbixHandleEvent($conn, $p);
    if (!$r['ok']) http_response_code($r['action'] === 'rejected' ? 400 : 500);
    echo json_encode(['success' => $r['ok'], 'action' => $r['action'], 'ticket_id' => $r['ticket_id']]
                     + (isset($r['error']) ? ['error' => $r['error']] : []));
} catch (Throwable $e) {
    error_log('Zabbix endpoint: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not process the alert.']);
}
