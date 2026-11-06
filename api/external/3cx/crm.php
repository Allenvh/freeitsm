<?php
/**
 * External endpoint: 3CX's CRM link (the "FreeITSM" template from
 * System → Integrations → 3CX).
 *
 * POST, JSON body, token as "Authorization: Bearer <token>":
 *   { "action": "lookup", "number": "[Number]" }
 *       → { "contacts": [ { id, first_name, last_name, email, company, phone, mobile, url } ] }
 *         one contact, or none when nobody (or more than one person) has the number
 *   { "action": "report", "call_type": "[CallType]", "number": "[Number]", "agent": "[Agent]",
 *     "agent_email": "[AgentEmail]", "agent_name": "...", "duration": "<seconds>",
 *     "start": "<UTC ISO time>", "recording_url": "[RecordingUrl]", "summary": "[Summary]" }
 *       → { "success": true, "action": "noted" | "created" | "logged" | "duplicate", "ticket_id": ... }
 * The token may instead be sent in the body as "token".
 *
 * No FreeITSM sign-in: the token, made on System → Integrations → 3CX, is the
 * credential. This path is on CSRF_EXEMPT_PATHS (api/external/) because 3CX is
 * cross-origin by design and never carries a login cookie.
 *
 * What a lookup and a report do: includes/integrations/threecx.php.
 */
require_once __DIR__ . '/../../../config.php';
require_once __DIR__ . '/../../../includes/functions.php';
require_once __DIR__ . '/../../../includes/integrations/threecx.php';

header('Content-Type: application/json');
header('Cache-Control: no-store');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    echo json_encode(['success' => false, 'error' => 'POST a JSON body.']);
    exit;
}

// A call report is small, even with 3CX's summary in it. Refuse anything that is not.
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
// 3CX sends a variable it has no value for as its own name - "[RecordingUrl]".
// That is "not sent", never text to store.
foreach ($p as $k => $v) {
    if (is_string($v) && preg_match('/^\[[A-Za-z]+\]$/', trim($v))) $p[$k] = '';
}

try {
    $conn = connectToDatabase();
    if (!threecxReady($conn)) {
        http_response_code(503);
        echo json_encode(['success' => false, 'error' => 'Run Database Verification first.']);
        exit;
    }
    $expected = threecxToken($conn);
    $given = (string)($p['token'] ?? '');
    if ($given === '') {
        // The header, wherever this server put it: getallheaders(), or $_SERVER
        // when FastCGI stripped it and .htaccess passed it on (as api/v1 reads it).
        $auth = '';
        foreach ((function_exists('getallheaders') ? getallheaders() : []) as $name => $value) {
            if (strtolower($name) === 'authorization') $auth = (string)$value;
        }
        if ($auth === '') $auth = (string)($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
        if (preg_match('/^Bearer\s+(\S+)$/i', trim($auth), $m)) $given = $m[1];
    }
    // No token made yet = the integration is off; never compare against ''.
    if ($expected === '' || $given === '' || !hash_equals($expected, $given)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'Bad token.']);
        exit;
    }

    $action = (string)($p['action'] ?? '');
    if ($action === 'lookup') {
        echo json_encode(threecxLookup($conn, trim((string)($p['number'] ?? ''))), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
    if ($action !== 'report') {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Unknown action.']);
        exit;
    }
    $r = threecxHandleCall($conn, $p);
    if (!$r['ok']) http_response_code($r['action'] === 'rejected' ? 400 : 500);
    echo json_encode(['success' => $r['ok'], 'action' => $r['action'], 'ticket_id' => $r['ticket_id']]
                     + (isset($r['error']) ? ['error' => $r['error']] : []));
} catch (Throwable $e) {
    error_log('3CX endpoint: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not process the request.']);
}
