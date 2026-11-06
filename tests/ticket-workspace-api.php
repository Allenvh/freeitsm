<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/** Disposable local fixtures; UI and REST must share flag, audit and scope rules. */
require __DIR__ . '/support/ticket-workspace-fixture.php';
$fixture = null;
$restore = null;
$workflowId = 0;
$checks = 0;
function workspaceAssert(bool $pass, string $label): void {
    global $checks;
    if (!$pass) throw new RuntimeException('FAIL: ' . $label);
    echo 'PASS: ' . $label . "\n";
    $checks++;
}
try {
    $fixture = ticketWorkspaceFixture();
    extract($fixture);
    require_once __DIR__ . '/../includes/ticket_workspace.php';
    require_once __DIR__ . '/../includes/preference_templates.php';
    $restore = ticketWorkspaceSetting($conn, 'ticket_workspace_mode', 'off');
    [$curl, $csrf] = ticketWorkspaceLogin($url, $analysts['full']);
    $page = ticketWorkspaceHttp($curl, $url . '/tickets/?layout=workspace');
    workspaceAssert(!str_contains($page['body'], 'id="ticketWorkspacePreference"')
        && str_contains($page['body'], '"mode":"off"'), 'off hides the personal switch and URL cannot override it');
    $prefs = ticketWorkspaceHttp($curl, $url . '/system/preferences/');
    workspaceAssert(!str_contains($prefs['body'], 'id="ticketWorkspacePref"'), 'System Preferences hides the switch when off');
    workspaceAssert(in_array('ticket_workspace', PREF_TEMPLATE_SECTIONS['ticket-workspace'][1], true), 'preference templates include the personal choice');

    $save = fn(array $body) => ticketWorkspaceHttp($curl, $url . '/api/tickets/assign_ticket.php', $body, $csrf);
    $r = $save(['ticket_id' => $ticket, 'workspace' => true]);
    workspaceAssert(($r['json']['success'] ?? false) === true, 'UI can set the flag through the existing writer');
    $readFlag = fn() => (int)$conn->query('SELECT is_workspace FROM tickets WHERE id = ' . $ticket)->fetchColumn();
    $auditCount = fn() => (int)$conn->query("SELECT COUNT(*) FROM ticket_audit WHERE ticket_id = $ticket AND field_name = 'Workspace'")->fetchColumn();
    workspaceAssert($readFlag() === 1 && $auditCount() === 1, 'flag and one audit are stored');
    $audit = $conn->query("SELECT old_value, new_value, analyst_id FROM ticket_audit WHERE ticket_id = $ticket AND field_name = 'Workspace'")->fetch(PDO::FETCH_ASSOC);
    workspaceAssert($audit['old_value'] === 'off' && $audit['new_value'] === 'on'
        && (int)$audit['analyst_id'] === $analysts['full']['id'], 'audit records on/off and the acting analyst');
    $save(['ticket_id' => $ticket, 'workspace' => true]);
    workspaceAssert($auditCount() === 1, 'repeated flag save is idempotent');
    $r = $save(['ticket_id' => $ticket, 'workspace' => 'false']);
    workspaceAssert(($r['json']['success'] ?? true) === false && $readFlag() === 1, 'string booleans are refused without mutation');
    $r = $save(['ticket_id' => $otherTicket, 'workspace' => true]);
    workspaceAssert(($r['json']['success'] ?? true) === false, 'UI cannot flag a ticket in another company');
    $r = ticketWorkspaceHttp($curl, $url . '/api/tickets/assign_ticket.php', ['ticket_id' => $ticket, 'workspace' => false], str_repeat('0', 64));
    workspaceAssert($r['status'] === 403 && $readFlag() === 1, 'CSRF is enforced');
    $r = ticketWorkspaceHttp($curl, $url . '/api/settings/save_system_settings.php', ['settings' => ['ticket_workspace_mode' => 'all']], $csrf);
    workspaceAssert($r['status'] === 403, 'ordinary analyst cannot enable the install setting');

    $conn->prepare("UPDATE system_settings SET setting_value = 'selected' WHERE setting_key = 'ticket_workspace_mode'")->execute();
    $page = ticketWorkspaceHttp($curl, $url . '/tickets/');
    workspaceAssert(str_contains($page['body'], 'id="ticketWorkspacePreference"') && str_contains($page['body'], '"personal":true'), 'enabled install defaults the personal switch on');
    ticketWorkspaceHttp($curl, $url . '/api/system/set_user_preference.php', ['key' => 'ticket_workspace', 'value' => '0'], $csrf);
    $page = ticketWorkspaceHttp($curl, $url . '/tickets/');
    workspaceAssert(str_contains($page['body'], '"personal":false'), 'personal opt-out survives a new request');
    $prefs = ticketWorkspaceHttp($curl, $url . '/system/preferences/');
    workspaceAssert(str_contains($prefs['body'], 'id="ticketWorkspacePref"'), 'System Preferences offers the same enabled switch');
    $conn->prepare("UPDATE system_settings SET setting_value = 'all' WHERE setting_key = 'ticket_workspace_mode'")->execute();
    workspaceAssert($readFlag() === 1, 'changing install mode preserves ticket flags');

    // Test-only keys never leave this process or enter a saved artifact.
    $makeKey = function (array $permissions) use ($insert, $analysts, $tenant): string {
        $key = 'fitsm_' . bin2hex(random_bytes(24));
        $insert('api_keys', ['name' => 'ZZ Workspace test', 'key_prefix' => substr($key, 0, 12),
            'key_hash' => hash('sha256', $key), 'analyst_id' => $analysts['full']['id'],
            'permissions' => json_encode(['tickets' => $permissions]), 'company_ids' => json_encode([$tenant])]);
        return $key;
    };
    $api = function (string $key, string $method, int $id, ?array $body = null) use ($url): array {
        $ch = curl_init($url . '/api/v1/index.php?path=' . rawurlencode('/tickets/' . $id));
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20, CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key]]
            + ($body === null ? [] : [CURLOPT_POSTFIELDS => json_encode($body)]));
        $raw = curl_exec($ch);
        return [(int)curl_getinfo($ch, CURLINFO_HTTP_CODE), json_decode((string)$raw, true)];
    };
    $key = $makeKey(['read', 'update']);
    [$status, $body] = $api($key, 'GET', $ticket);
    workspaceAssert($status === 200 && ($body['data']['workspace'] ?? null) === true, 'REST exposes a real boolean');
    [$status, $body] = $api($key, 'PATCH', $ticket, ['workspace' => false]);
    workspaceAssert($status === 200 && ($body['data']['workspace'] ?? null) === false && $auditCount() === 2, 'REST update shares the UI flag and audit');
    [$status] = $api($makeKey(['read']), 'PATCH', $ticket, ['workspace' => true]);
    workspaceAssert($status === 403 && $readFlag() === 0, 'read-only REST key cannot change presentation');
    [$status] = $api($key, 'PATCH', $otherTicket, ['workspace' => true]);
    workspaceAssert(in_array($status, [403, 404], true), 'REST retains company scope');
    [$status] = $api($key, 'PATCH', $ticket, ['workspace' => 1]);
    workspaceAssert($status === 422 && $readFlag() === 0, 'REST refuses integer booleans');
    [$status] = $api($key, 'PATCH', $ticket, ['workspace' => true, 'priority_id' => -123]);
    workspaceAssert($status === 422 && $readFlag() === 0, 'invalid mixed update does not partially set the flag');

    // A real workflow with no actions is the positive control: mixed updates
    // must still reach the engine. Refuse an install with existing matching rules.
    if ((int)$conn->query("SELECT COUNT(*) FROM workflows WHERE is_active = 1 AND trigger_event = 'ticket.priority_changed'")->fetchColumn()) {
        throw new RuntimeException('Use a test database without active ticket.priority_changed workflows.');
    }
    $workflowId = $insert('workflows', ['name' => 'ZZ Workspace event control', 'trigger_event' => 'ticket.priority_changed',
        'conditions' => json_encode([['field' => 'ticket.id', 'op' => 'eq', 'value' => $ticket]]), 'actions' => '[]', 'tenant_id' => $tenant]);
    $runs = fn() => (int)$conn->query('SELECT COUNT(*) FROM workflow_executions WHERE workflow_id = ' . $workflowId)->fetchColumn();
    $api($key, 'PATCH', $ticket, ['workspace' => true]);
    workspaceAssert($runs() === 0, 'flag-only change does not trigger a workflow');
    $priority = (int)$conn->query('SELECT MIN(id) FROM ticket_priorities')->fetchColumn();
    if (!$priority) throw new RuntimeException('The local test installation needs a ticket priority.');
    [$status] = $api($key, 'PATCH', $ticket, ['workspace' => false, 'priority_id' => $priority]);
    workspaceAssert($status === 200 && $runs() === 1 && $readFlag() === 0, 'mixed flag and priority update still fires the normal event');

    $conn->prepare('UPDATE analysts SET is_admin = 1 WHERE id = ?')->execute([$analysts['read']['id']]);
    [$adminCurl, $adminCsrf] = ticketWorkspaceLogin($url, $analysts['read']);
    $r = ticketWorkspaceHttp($adminCurl, $url . '/api/settings/save_system_settings.php', ['settings' => ['ticket_workspace_mode' => 'invalid']], $adminCsrf);
    workspaceAssert($r['status'] === 400 && ticketWorkspaceMode($conn) === TicketWorkspaceMode::All, 'invalid install enum is rejected before writing');
    echo "$checks workspace API checks passed\n";
} finally {
    if ($workflowId && isset($conn)) $conn->prepare('DELETE FROM workflow_executions WHERE workflow_id = ?')->execute([$workflowId]);
    if ($restore) $restore();
    if ($fixture) ($fixture['cleanup'])();
}
