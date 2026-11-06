<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The portal shows a requester the answers they gave (discussion #193).
 *
 *   php tests/portal-field-answers.php
 *
 * Through the real endpoint, api/self-service/get_ticket_detail.php:
 * 1. A field the requester is asked comes back with its answer; a field only
 *    analysts fill in never does, whatever its value.
 * 2. An unanswered field is left out.
 * 3. Tickets -> Settings -> Custom fields -> "Show requesters the answers they
 *    gave": never saved = 'header'; 'side' and 'off' are passed through, and
 *    'off' sends no answers at all.
 * 4. Somebody else's ticket answers "not found" - the answers ride on the same
 *    access rule as the conversation.
 *
 * Throwaway fixtures (a ZZ portal user, a ZZ ticket inserted directly so no
 * workflow fires, three ZZ fields) are deleted in `finally`, and the setting is
 * put back exactly as it was.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';

$pass = 0; $fail = 0;
/**
 * Records and prints one PASS or FAIL line.
 *
 * @param string $what  what is being checked, in a few words
 * @param bool $cond  true when the check passed
 * @param string $detail  shown after a failure - the values that disagreed
 */
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

/**
 * Runs the portal ticket endpoint in this process as a portal user and returns its answer.
 *
 * @param int $userId  the portal user ($_SESSION['ss_user_id'])
 * @param int $ticketId  the ticket asked for
 */
function detail(int $userId, int $ticketId): array {
    $endpoint = realpath(__DIR__ . '/../api/self-service/get_ticket_detail.php');
    $_SESSION = ['ss_user_id' => $userId];
    $_GET = ['ticket_id' => (string)$ticketId];
    $cwd = getcwd();
    // After this script has printed, the endpoint's session_start() and header()
    // can only warn on the command line. Swallow exactly those two.
    set_error_handler(fn($no, $msg) => stripos($msg, 'session') !== false || stripos($msg, 'headers already sent') !== false);
    ob_start();
    try {
        chdir(dirname($endpoint));
        include $endpoint;
    } finally {
        $raw = (string)ob_get_clean();
        chdir($cwd);
        restore_error_handler();
    }
    return json_decode(trim($raw), true) ?: ['raw' => substr($raw, 0, 300)];
}

/**
 * Sets (or, with null, removes) the placement setting.
 *
 * @param PDO $conn  the database
 * @param ?string $value  'off' | 'header' | 'side', or null for "never saved"
 */
function setPlace(PDO $conn, ?string $value): void {
    $conn->prepare("DELETE FROM system_settings WHERE setting_key = 'ticket_portal_field_answers'")->execute();
    if ($value !== null) {
        $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('ticket_portal_field_answers', ?)")->execute([$value]);
    }
}

echo "\nPortal: the answers a requester gave (discussion #193)\n" . str_repeat('=', 70) . "\n";
$conn = connectToDatabase();
$original = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'ticket_portal_field_answers'")->fetchColumn();
$user = 0; $other = 0; $ticket = 0; $fields = [];
/**
 * Cleanup and the summary, as a SHUTDOWN function rather than a `finally`.
 *
 * TRAP: the endpoint ends a refused request with `exit`, and `exit` skips
 * `finally` - the first version of this test left its fixtures behind exactly
 * that way. A shutdown function still runs. The "not found" check is therefore
 * the LAST call, and it is judged here, from the output the endpoint left in the
 * buffer when it exited.
 */
register_shutdown_function(function () {
    global $conn, $original, $user, $other, $ticket, $fields, $pass, $fail, $pendingNotFound;
    if ($pendingNotFound) {
        $raw = '';
        while (ob_get_level() > 0) $raw = ob_get_clean() . $raw;
        chdir(__DIR__);
        $d = json_decode(trim($raw), true) ?: [];
        ok("somebody else's ticket is not found, answers and all", empty($d['success']) && !isset($d['field_answers']), trim($raw));
    }
    setPlace($conn, $original === false ? null : (string)$original);
    if ($ticket) {
        $conn->prepare("DELETE FROM ticket_field_values WHERE ticket_id = ?")->execute([$ticket]);
        $conn->prepare("DELETE FROM tickets WHERE id = ? AND ticket_number = 'ZZ-TEST-193'")->execute([$ticket]);
    }
    foreach ($fields as $id) {
        $conn->prepare("DELETE FROM ticket_field_values WHERE field_id = ?")->execute([$id]);
        $conn->prepare("DELETE FROM ticket_fields WHERE id = ? AND field_key LIKE 'zz_t193_%'")->execute([$id]);
    }
    foreach ([$user, $other] as $id) {
        if ($id) $conn->prepare("DELETE FROM users WHERE id = ? AND email LIKE 'zz-test-193%'")->execute([$id]);
    }
    $left = (int)$conn->query("SELECT (SELECT COUNT(*) FROM tickets WHERE ticket_number = 'ZZ-TEST-193') + (SELECT COUNT(*) FROM ticket_fields WHERE field_key LIKE 'zz_t193_%') + (SELECT COUNT(*) FROM users WHERE email LIKE 'zz-test-193%')")->fetchColumn();
    $now = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'ticket_portal_field_answers'")->fetchColumn();
    ok('cleanup: fixtures gone and the setting is as it was', $left === 0 && $now === $original, "{$left} left");
    echo "\n{$pass} passed, {$fail} failed\n";
    exit($fail ? 1 : 0);
});
$pendingNotFound = false;

    $conn->exec("INSERT INTO users (email, display_name) VALUES ('zz-test-193@invalid.test', 'ZZ test 193')");
    $user = (int)$conn->lastInsertId();
    $conn->exec("INSERT INTO users (email, display_name) VALUES ('zz-test-193-other@invalid.test', 'ZZ test 193 other')");
    $other = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO tickets (ticket_number, subject, user_id, created_datetime) VALUES ('ZZ-TEST-193', 'ZZ test 193', ?, UTC_TIMESTAMP())")->execute([$user]);
    $ticket = (int)$conn->lastInsertId();

    // Three shared fields on every type: asked + answered, asked + unanswered, analyst-only + answered.
    $mk = $conn->prepare("INSERT INTO ticket_fields (field_key, label, field_type, all_types, ask_requester, display_order) VALUES (?, ?, ?, 1, ?, 9999)");
    foreach ([['zz_t193_asked', 'ZZ Start date', 'date', 1], ['zz_t193_blank', 'ZZ Laptop', 'text', 1], ['zz_t193_internal', 'ZZ Cost code', 'text', 0]] as [$k, $l, $ty, $ask]) {
        $mk->execute([$k, $l, $ty, $ask]);
        $fields[$k] = (int)$conn->lastInsertId();
    }
    $conn->prepare("INSERT INTO ticket_field_values (ticket_id, field_id, value_date) VALUES (?, ?, '2026-11-03 00:00:00')")->execute([$ticket, $fields['zz_t193_asked']]);
    $conn->prepare("INSERT INTO ticket_field_values (ticket_id, field_id, value_text) VALUES (?, ?, 'SECRET-CC-1')")->execute([$ticket, $fields['zz_t193_internal']]);

    setPlace($conn, null);
    $d = detail($user, $ticket);
    $items = $d['field_answers']['items'] ?? null;
    $labels = array_column($items ?? [], 'label');
    ok('the endpoint answers', !empty($d['success']), json_encode($d));
    ok('never saved = under the title', ($d['field_answers']['place'] ?? null) === 'header', json_encode($d['field_answers'] ?? null));
    ok('the answered requester field is there', in_array('ZZ Start date', $labels, true), json_encode($labels));
    $asked = array_values(array_filter($items ?? [], fn($i) => $i['label'] === 'ZZ Start date'))[0] ?? [];
    ok('...with its answer', ($asked['display'] ?? '') === '2026-11-03', json_encode($asked));
    ok('an unanswered field is left out', !in_array('ZZ Laptop', $labels, true));
    ok('an analyst-only field is never sent', !in_array('ZZ Cost code', $labels, true) && !str_contains(json_encode($d), 'SECRET-CC-1'));

    setPlace($conn, 'side');
    ok("'side' is passed through", (detail($user, $ticket)['field_answers']['place'] ?? null) === 'side');
    setPlace($conn, 'off');
    $d = detail($user, $ticket);
    ok("'off' sends no answers", ($d['field_answers']['place'] ?? null) === 'off' && ($d['field_answers']['items'] ?? null) === []);

    // Last, because a refused request ends with `exit` - judged in the shutdown function above.
    setPlace($conn, null);
    $pendingNotFound = true;
    detail($other, $ticket);
