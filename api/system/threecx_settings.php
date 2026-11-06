<?php
/**
 * API: System → Integrations → 3CX. Administrators only.
 *
 *   GET  ?action=get     settings, the lookups the form needs, the endpoint address,
 *                        the token, and the last 25 calls reported
 *   GET  ?action=export&endpoint=…   the CRM template, as a file to upload into 3CX
 *   POST {action:'save', ...}    the settings - see threecxSaveSettings()
 *   POST {action:'rotate'}       a new token; the old one stops working at once
 *   POST {action:'try', number, call_type, agent_email}
 *                        what a call would do - the same decision a real report
 *                        takes (threecxPlanCall), with NOTHING written
 *
 * The token is returned in full because the administrator hands it to 3CX. It is
 * encrypted at rest (ENCRYPTED_SETTING_KEYS).
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/admin_api_guard.php';   // administrators only
require_once '../../includes/public_url.php';
require_once '../../includes/integrations/threecx.php';

header('Content-Type: application/json');

try {
    $conn = connectToDatabase();
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $action = (string)($in['action'] ?? $_GET['action'] ?? 'get');

    if ($action === 'save') {
        csrfRequireWriteMethod();
        threecxSaveSettings($conn, $in);
        echo json_encode(['success' => true]);
        exit;
    }
    if ($action === 'rotate') {
        csrfRequireWriteMethod();
        echo json_encode(['success' => true, 'token' => threecxRotateToken($conn)]);
        exit;
    }
    if ($action === 'export') {
        // The template as a file for 3CX. A download, not a change: it carries the
        // token, which only an administrator reaches this far to see.
        $token = threecxToken($conn);
        if ($token === '') throw new InvalidArgumentException('Make a token first.');
        $endpoint = trim((string)($_GET['endpoint'] ?? '')) ?: publicAbsoluteUrl($conn, 'api/external/3cx/crm.php');
        if (!preg_match('#^https?://\S+$#i', $endpoint)) throw new InvalidArgumentException('The address must start with http:// or https://');
        header('Content-Type: application/xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="freeitsm_3cx_template.xml"');
        header('Cache-Control: no-store');
        echo threecxTemplateXml($endpoint, $token);
        exit;
    }
    if ($action === 'try') {
        // A POST because it is the page's button, but it changes nothing: the plan
        // is worked out and described, never carried out - a test call against a
        // real requester must not put a note on their real ticket.
        csrfRequireWriteMethod();
        $c = threecxNormaliseCall([
            'number'      => (string)($in['number'] ?? ''),
            'call_type'   => (string)($in['call_type'] ?? 'Inbound'),
            'agent_email' => (string)($in['agent_email'] ?? ''),
        ]);
        if ($c['number'] === '') throw new InvalidArgumentException('Type a phone number.');
        $plan = threecxPlanCall($conn, $c, threecxSettings($conn));
        $ticketNumber = null;
        if ($plan['ticket_id']) {
            $n = $conn->prepare("SELECT ticket_number FROM tickets WHERE id = ?");
            $n->execute([$plan['ticket_id']]);
            $ticketNumber = $n->fetchColumn() ?: null;
        }
        echo json_encode([
            'success'         => true,
            'caller'          => $plan['caller'] ? ['id' => $plan['caller']['id'], 'name' => $plan['caller']['display_name']] : null,
            'callers'         => $plan['callers'],
            'author'          => $plan['author'] ? $plan['author']['name'] : null,
            'author_is_agent' => $plan['author_is_agent'],
            'action'          => $plan['action'],
            'create'          => $plan['create'],
            'ticket_id'       => $plan['ticket_id'],
            'ticket_number'   => $ticketNumber,
            'detail'          => $plan['detail'],
        ]);
        exit;
    }
    if ($action !== 'get') {
        echo json_encode(['success' => false, 'error' => 'Unknown action']);
        exit;
    }

    $calls = [];
    if (threecxReady($conn)) {
        $calls = $conn->query("SELECT c.id, c.call_type, c.number, c.agent, c.duration_seconds, c.ticket_id, c.action, c.detail,
                                      c.received_datetime, t.ticket_number,
                                      COALESCE(NULLIF(u.display_name, ''), u.email) AS caller_name, a.full_name AS analyst_name
                                 FROM threecx_calls c
                            LEFT JOIN tickets t ON t.id = c.ticket_id
                            LEFT JOIN users u ON u.id = c.user_id
                            LEFT JOIN analysts a ON a.id = c.analyst_id
                                ORDER BY c.id DESC LIMIT 25")->fetchAll(PDO::FETCH_ASSOC);
    }
    $multi = isMultiTenant($conn);
    echo json_encode([
        'success'    => true,
        'ready'      => threecxReady($conn),
        'settings'   => threecxSettings($conn),
        'token'      => threecxToken($conn),
        'endpoint'   => publicAbsoluteUrl($conn, 'api/external/3cx/crm.php'),
        'me'         => (string)($conn->query("SELECT email FROM analysts WHERE id = " . (int)$_SESSION['analyst_id'])->fetchColumn() ?: ''),
        'analysts'   => $conn->query("SELECT id, full_name AS name FROM analysts WHERE is_active = 1 ORDER BY full_name")->fetchAll(PDO::FETCH_ASSOC),
        'teams'      => $conn->query("SELECT id, name FROM teams WHERE COALESCE(is_active, 1) = 1 ORDER BY name")->fetchAll(PDO::FETCH_ASSOC),
        'origins'    => $conn->query("SELECT id, name FROM ticket_origins WHERE COALESCE(is_active, 1) = 1 ORDER BY display_order, name")->fetchAll(PDO::FETCH_ASSOC),
        'companies'  => $multi ? $conn->query("SELECT id, name FROM tenants ORDER BY name")->fetchAll(PDO::FETCH_ASSOC) : [],
        'calls'      => $calls,
    ]);
} catch (InvalidArgumentException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    error_log('threecx_settings: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Could not load or save the 3CX settings.']);
}
