<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Which analysts get a folder in the inbox, by company (GH #176).
 *
 *   php tests/analyst-folders-by-company.php
 *
 * On a multi-company install the Analyst folders listed EVERY active analyst,
 * so one client's analysts saw another client's by name. The rule now
 * (api/tickets/get_ticket_counts.php): an analyst gets a folder if they can
 * access the company being viewed ("All companies" = any company the viewer can
 * access), or they are the viewer, or they hold tickets in scope.
 *
 * Drives the REAL endpoint in-process. The analyst under test is a throwaway
 * ("zz-test-folders") who can access exactly ONE company; it has an unusable
 * password hash, so nobody can sign in as it, and it is deleted by id in a
 * finally block, then checked gone. It writes nothing else.
 *
 * Needs a multi-company install (two or more companies); skips otherwise.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';

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
 * Runs the counts endpoint in this process with the session values given and
 * returns the ids of the analysts it gives a folder to.
 *
 * @param array $session  the $_SESSION the endpoint should see: analyst_id, active_tenant_id, active_tenant_all
 */
function folderAnalysts(array $session): array {
    $endpoint = realpath(__DIR__ . '/../api/tickets/get_ticket_counts.php');
    $_SESSION = $session;
    $cwd = getcwd();
    // After this script has printed, the endpoint's session_start() and header()
    // can only warn on the command line. Swallow exactly those two.
    set_error_handler(function ($no, $msg) {
        return stripos($msg, 'session') !== false || stripos($msg, 'headers already sent') !== false;
    });
    ob_start();
    try {
        chdir(dirname($endpoint));
        include $endpoint;
    } finally {
        $raw = (string)ob_get_clean();
        chdir($cwd);
        restore_error_handler();
    }
    $d = json_decode(trim($raw), true);
    if (!is_array($d) || empty($d['success'])) {
        echo "  FAIL  the endpoint did not answer: " . substr(trim($raw), 0, 300) . "\n";
        exit(1);
    }
    return array_map('intval', array_column($d['analysts'], 'id'));
}

echo "\nAnalyst folders follow the company being viewed (GH #176)\n" . str_repeat('=', 70) . "\n";
$conn = connectToDatabase();
if (!isMultiTenant($conn)) { echo "  SKIP  single-company install - the folder list is deliberately unchanged there\n"; exit(0); }

$tenants = array_map('intval', $conn->query("SELECT id FROM tenants ORDER BY id")->fetchAll(PDO::FETCH_COLUMN));
// The viewer: an active analyst with the Tickets module who can access at least two companies.
$viewer = 0; $mine = [];
foreach ($conn->query("SELECT id FROM analysts WHERE is_active = 1 ORDER BY id")->fetchAll(PDO::FETCH_COLUMN) as $id) {
    $acc = getAccessibleTenantIds($conn, (int)$id);
    if (count($acc) >= 2 && analystCanAccessModule($conn, (int)$id, 'tickets')) { $viewer = (int)$id; $mine = $acc; break; }
}
if (!$viewer) { echo "  SKIP  no analyst can access two companies\n"; exit(0); }
$there = $mine[1];                   // the one company the throwaway analyst can access
$elsewhere = $mine[0];               // a company the viewer can access and the throwaway cannot
echo "  (viewer #{$viewer}; throwaway analyst can access company #{$there} only)\n";

$zz = 0;
try {
    $conn->prepare("INSERT INTO analysts (username, email, full_name, password_hash, is_active, can_access_all_tenants)
                    VALUES ('zz-test-folders', 'zz-test-folders@invalid.test', 'ZZ test folders', '!', 1, 0)")->execute();
    $zz = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$zz, $there]);
    $zzTickets = (int)$conn->query("SELECT COUNT(*) FROM tickets WHERE assigned_analyst_id = $zz")->fetchColumn();
    ok('positive control: the throwaway analyst can access exactly one company',
       getAccessibleTenantIds($conn, $zz) === [$there] && $zzTickets === 0);

    $session = ['analyst_id' => $viewer, 'active_tenant_all' => false];

    $inThere = folderAnalysts($session + ['active_tenant_id' => $there]);
    ok("viewing the company they can access: they get a folder", in_array($zz, $inThere, true));

    $inElsewhere = folderAnalysts($session + ['active_tenant_id' => $elsewhere]);
    ok("viewing a company they cannot access: no folder", !in_array($zz, $inElsewhere, true));
    ok("viewing that company: the viewer still has their own folder", in_array($viewer, $inElsewhere, true));

    $inAll = folderAnalysts(['analyst_id' => $viewer, 'active_tenant_id' => $elsewhere, 'active_tenant_all' => true]);
    ok("All companies: they get a folder (they share a company with the viewer)", in_array($zz, $inAll, true));

    // Nobody who CAN access the company lost their folder: every real analyst with
    // access to $elsewhere is still listed there.
    $should = [];
    foreach ($conn->query("SELECT id FROM analysts WHERE is_active = 1 AND id <> $zz")->fetchAll(PDO::FETCH_COLUMN) as $id) {
        if (in_array($elsewhere, getAccessibleTenantIds($conn, (int)$id), true)) $should[] = (int)$id;
    }
    $missing = array_diff($should, $inElsewhere);
    ok('nobody who can access the company lost their folder (' . count($should) . ' analysts)', !$missing,
       'missing: ' . implode(', ', $missing));
} finally {
    if ($zz) {
        $conn->prepare("DELETE FROM analyst_tenant_access WHERE analyst_id = ?")->execute([$zz]);
        $conn->prepare("DELETE FROM analysts WHERE id = ? AND username = 'zz-test-folders'")->execute([$zz]);
    }
}
$left = (int)$conn->query("SELECT COUNT(*) FROM analysts WHERE username = 'zz-test-folders'")->fetchColumn();
ok('cleanup: the throwaway analyst is gone', $left === 0, "{$left} left");

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
