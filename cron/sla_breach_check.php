<?php
/**
 * SLA Breach Check — cron entry point.
 *
 * Walks every open SLA-tracked ticket, fires warning + breach email
 * notifications according to the rules configured under
 * Tickets > Settings > SLA > Breach Notifications. Dedups via the
 * sla_notifications_sent table so each (ticket, target, trigger) fires
 * at most once.
 *
 * Run on a schedule (every 5 minutes is the sweet spot — frequent enough
 * to catch breaches promptly, sparse enough to avoid hammering the
 * mailbox API). See docs/sla-cron-setup.md for Windows Task Scheduler +
 * Linux cron configuration.
 *
 * Invocation (CLI):  php c:/wamp64/www/freeitsm-app/cron/sla_breach_check.php
 * Invocation (web):  curl http://localhost/freeitsm-app/cron/sla_breach_check.php
 *
 * When invoked via HTTP, the script requires a `?token=<value>` query string
 * matching the `sla_cron_token` value in system_settings — set the token
 * once during install so the endpoint isn't open to the world. CLI runs
 * skip the token check (the OS already gates filesystem access).
 *
 * Output: plaintext summary lines (sent / skipped / errors), suitable for
 * Task Scheduler log capture or a cron-mailed report.
 */

// Hard-cap runtime so a misconfigured install can't hang
set_time_limit(120);
header('Content-Type: text/plain; charset=utf-8');

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/sla_notifications.php';

$isCli = (PHP_SAPI === 'cli');

try {
    $conn = connectToDatabase();

    // HTTP gate: shared-secret token. CLI invocations skip this.
    if (!$isCli) {
        $expected = null;
        try {
            $stmt = $conn->prepare("SELECT setting_value FROM system_settings WHERE setting_key = 'sla_cron_token' LIMIT 1");
            $stmt->execute();
            $expected = $stmt->fetchColumn() ?: null;
        } catch (Exception $e) {
            // Table missing / not seeded — fall through, will error below
        }
        if (empty($expected)) {
            http_response_code(503);
            echo "Cron token not set. Add a sla_cron_token row to system_settings (any random string) before invoking this endpoint over HTTP.\n";
            exit;
        }
        $supplied = $_GET['token'] ?? '';
        if (!hash_equals($expected, (string)$supplied)) {
            http_response_code(403);
            echo "Forbidden\n";
            exit;
        }
    }

    $start = microtime(true);
    $summary = sla_run_breach_check($conn);
    $elapsed = round((microtime(true) - $start) * 1000);

    echo "SLA breach check completed in {$elapsed}ms\n";
    echo "  Sent:    " . count($summary['sent']) . "\n";
    echo "  Skipped: " . count($summary['skipped']) . "\n";
    echo "  Errors:  " . count($summary['errors']) . "\n\n";

    if (!empty($summary['sent'])) {
        echo "SENT:\n";
        foreach ($summary['sent'] as $line) echo "  - $line\n";
        echo "\n";
    }
    if (!empty($summary['errors'])) {
        echo "ERRORS:\n";
        foreach ($summary['errors'] as $line) echo "  - $line\n";
        echo "\n";
    }
    // Skipped lines are noisy on quiet systems — only print if asked via ?verbose=1
    if (!empty($summary['skipped']) && (!empty($_GET['verbose']) || ($isCli && in_array('--verbose', $argv ?? [], true)))) {
        echo "SKIPPED:\n";
        foreach ($summary['skipped'] as $line) echo "  - $line\n";
    }
} catch (Exception $e) {
    http_response_code(500);
    echo "ERROR: " . $e->getMessage() . "\n";
    error_log("SLA breach cron error: " . $e->getMessage());
    exit(1);
}
