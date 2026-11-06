<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Webhook delivery settings (System > Webhooks) - tries before giving up, the
 * wait for the endpoint, and what happens to a send the worker never finished.
 *
 * Written after a worker killed mid-send (its 120s time limit, a restart) left
 * the row it had claimed in 'delivering' for ever: never retried, never
 * replayable, and counted as "in flight" on the dashboard until the row was
 * pruned - which it never was, as only delivered and dead rows are pruned.
 *
 * Never calls webhookRunQueue(): that would send the install's real queue.
 * Everything runs inside ONE transaction that is always rolled back, settings
 * rows included. Delivery rows are ZZ-prefixed and pointed at example.invalid.
 *
 * Run: php tests/webhook-delivery-settings.php
 */

$root = dirname(__DIR__);
require_once "$root/config.php";
require_once "$root/includes/functions.php";
require_once "$root/includes/webhook_delivery.php";

$pass = 0; $fail = 0;
function ok(string $label, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; printf("  PASS %-70s\n", $label); }
    else       { $fail++; printf("  FAIL %-70s %s\n", $label, $detail); }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function setSetting(PDO $conn, string $k, ?string $v): void {
    $conn->prepare("DELETE FROM system_settings WHERE setting_key = ?")->execute([$k]);
    if ($v !== null) $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)")->execute([$k, $v]);
}
/** A delivery claimed $ageSec ago that nobody finished. */
function stuckRow(PDO $conn, string $preset, int $attempts, int $max, int $ageSec, string $status = 'delivering'): int {
    $conn->prepare(
        "INSERT INTO webhook_deliveries (preset, url, method, request_headers, request_body, status, attempts, max_attempts,
                                         next_attempt_at, created_datetime, updated_datetime)
         VALUES (?, 'https://example.invalid/zz', 'POST', '[]', '{}', ?, ?, ?, NULL,
                 UTC_TIMESTAMP() - INTERVAL ? SECOND, UTC_TIMESTAMP() - INTERVAL ? SECOND)"
    )->execute([$preset, $status, $attempts, $max, $ageSec, $ageSec]);
    return (int)$conn->lastInsertId();
}
function row(PDO $conn, int $id): array {
    $s = $conn->prepare("SELECT status, attempts, last_error, TIMESTAMPDIFF(SECOND, UTC_TIMESTAMP(), next_attempt_at) AS wait_s
                           FROM webhook_deliveries WHERE id = ?");
    $s->execute([$id]);
    return $s->fetch(PDO::FETCH_ASSOC) ?: [];
}

echo "\nWebhook delivery settings\n" . str_repeat('=', 72) . "\n";
$conn->beginTransaction();
try {
    echo "\nDefaults are what FreeITSM did before the settings existed\n";
    foreach ([WEBHOOK_ATTEMPTS_SETTING, WEBHOOK_TIMEOUT_SETTING, WEBHOOK_INTERRUPTED_SETTING] as $k) setSetting($conn, $k, null);
    $cfg = webhookDeliverySettings($conn);
    ok('6 tries', $cfg['max_attempts'] === 6, var_export($cfg['max_attempts'], true));
    ok('15 second wait', $cfg['timeout'] === 15, var_export($cfg['timeout'], true));
    ok('an interrupted send is retried', $cfg['interrupted'] === WEBHOOK_INTERRUPTED_RETRY);

    echo "\nSaved values are read; values the page never offers are not\n";
    setSetting($conn, WEBHOOK_ATTEMPTS_SETTING, '3');
    setSetting($conn, WEBHOOK_TIMEOUT_SETTING, '60');
    setSetting($conn, WEBHOOK_INTERRUPTED_SETTING, 'stop');
    $cfg = webhookDeliverySettings($conn);
    ok('POSITIVE CONTROL: 3 tries is read', $cfg['max_attempts'] === 3);
    ok('...60 seconds is read', $cfg['timeout'] === 60);
    ok('...stop is read', $cfg['interrupted'] === WEBHOOK_INTERRUPTED_STOP);
    setSetting($conn, WEBHOOK_ATTEMPTS_SETTING, '500');
    setSetting($conn, WEBHOOK_TIMEOUT_SETTING, '0');
    setSetting($conn, WEBHOOK_INTERRUPTED_SETTING, 'Retry');
    $cfg = webhookDeliverySettings($conn);
    ok('500 tries falls back to 6', $cfg['max_attempts'] === 6);
    ok('a 0 second wait falls back to 15', $cfg['timeout'] === 15);
    ok('"Retry" (wrong case) falls back to retry, not stop', $cfg['interrupted'] === WEBHOOK_INTERRUPTED_RETRY);

    echo "\nA new delivery takes its tries from the setting, and keeps them\n";
    setSetting($conn, WEBHOOK_ATTEMPTS_SETTING, '10');
    $id = webhookEnqueue($conn, ['preset' => 'zz-enq', 'url' => 'https://example.invalid/zz', 'headers' => [], 'body' => '{}']);
    $max = (int)$conn->query("SELECT max_attempts FROM webhook_deliveries WHERE id = $id")->fetchColumn();
    ok('queued with 10 tries', $max === 10, "got $max");
    $id2 = webhookEnqueue($conn, ['preset' => 'zz-enq', 'url' => 'https://example.invalid/zz', 'headers' => [], 'body' => '{}', 'max_attempts' => 2]);
    ok('...unless the caller names a number', (int)$conn->query("SELECT max_attempts FROM webhook_deliveries WHERE id = $id2")->fetchColumn() === 2);
    setSetting($conn, WEBHOOK_ATTEMPTS_SETTING, '1');
    ok('changing the setting later leaves a queued row alone',
       (int)$conn->query("SELECT max_attempts FROM webhook_deliveries WHERE id = $id")->fetchColumn() === 10);

    echo "\nInterrupted sends, 'retry'\n";
    $old    = stuckRow($conn, 'zz-old', 0, 6, 1200);      // claimed 20 minutes ago
    $last   = stuckRow($conn, 'zz-last', 5, 6, 1200);     // its sixth and last try was the one cut off
    $recent = stuckRow($conn, 'zz-recent', 0, 6, 60);     // claimed a minute ago: may still be sending
    $failed = stuckRow($conn, 'zz-failed', 1, 6, 1200, 'failed');
    $n = webhookRecoverInterrupted($conn, WEBHOOK_INTERRUPTED_RETRY);
    $r = row($conn, $old);
    ok('a send cut off 20 minutes ago becomes a failed try', $r['status'] === 'failed' && (int)$r['attempts'] === 1, json_encode($r));
    ok('...retried on the normal 1 minute backoff', (int)$r['wait_s'] > 0 && (int)$r['wait_s'] <= 60, 'wait ' . $r['wait_s']);
    ok('...and says why', str_starts_with((string)$r['last_error'], 'Interrupted'));
    $r = row($conn, $last);
    ok('a cut-off LAST try goes dead, not round again', $r['status'] === 'dead' && (int)$r['attempts'] === 6, json_encode($r));
    ok('CONTROL: one claimed a minute ago is left alone', row($conn, $recent)['status'] === 'delivering');
    ok('CONTROL: a failed row is not touched', (int)row($conn, $failed)['attempts'] === 1);
    ok('the count includes both settled rows', $n >= 2, "got $n");

    echo "\nInterrupted sends, 'stop'\n";
    $stop = stuckRow($conn, 'zz-stop', 0, 6, 1200);
    webhookRecoverInterrupted($conn, WEBHOOK_INTERRUPTED_STOP);
    $r = row($conn, $stop);
    ok('goes dead straight away', $r['status'] === 'dead' && (int)$r['attempts'] === 1, json_encode($r));
    ok('...where Replay can pick it up', webhookReplay($conn, $stop));
    ok('...and Replay queues it again', row($conn, $stop)['status'] === 'pending');
} finally {
    $conn->rollBack();
}

$left = (int)$conn->query("SELECT COUNT(*) FROM webhook_deliveries WHERE preset LIKE 'zz-%'")->fetchColumn();
ok('nothing left behind', $left === 0, "$left ZZ rows");

printf("\n%d passed, %d failed\n", $pass, $fail);
exit($fail ? 1 : 0);
