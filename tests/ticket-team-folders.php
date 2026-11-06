<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Team folders and the assign lists (GH #176, second part).
 *
 *   php tests/ticket-team-folders.php
 *
 * 1. Tickets -> Settings -> General -> Team folders. 'all' (the default) lists
 *    every team; 'mine' lists only the teams the analyst belongs to, plus any team
 *    holding tickets they can already see. Administrators always see every team.
 * 2. api/tickets/get_analysts.php?with_access=1 gives each active analyst's
 *    accessible companies, decided by getAccessibleTenantIds() - the inbox uses
 *    it to offer only people who can work in the ticket's company.
 *
 * Throwaway fixtures (one ZZ analyst, two ZZ teams) are deleted in `finally`, and
 * the setting is put back exactly as it was. No ticket is created.
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
 * Runs an endpoint in this process with the session and query given, and returns
 * its decoded answer.
 *
 * @param string $file  the endpoint, relative to api/tickets
 * @param array $session  the $_SESSION it should see
 * @param array $get  the $_GET it should see
 */
function callEndpoint(string $file, array $session, array $get = []): array {
    $endpoint = realpath(__DIR__ . '/../api/tickets/' . $file);
    $_SESSION = $session;
    $_GET = $get;
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
        echo "  FAIL  {$file} did not answer: " . substr(trim($raw), 0, 300) . "\n";
        exit(1);
    }
    return $d;
}

/**
 * The ids of the team folders the counts endpoint gives this session.
 *
 * @param array $session  the $_SESSION: analyst_id, active_tenant_id, active_tenant_all
 */
function teamFolders(array $session): array {
    return array_map('intval', array_column(callEndpoint('get_ticket_counts.php', $session)['teams'] ?? [], 'id'));
}

/**
 * Sets (or, with null, removes) the team folders setting.
 *
 * @param PDO $conn  the database
 * @param ?string $value  'all', 'mine', or null for "not set"
 */
function setTeamFolders(PDO $conn, ?string $value): void {
    $conn->prepare("DELETE FROM system_settings WHERE setting_key = 'ticket_team_folders'")->execute();
    if ($value !== null) {
        $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('ticket_team_folders', ?)")->execute([$value]);
    }
}

echo "\nTeam folders and the assign lists (GH #176)\n" . str_repeat('=', 70) . "\n";
$conn = connectToDatabase();

$original = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'ticket_team_folders'")->fetchColumn();
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_admin = 1 AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
$tenant = (int)$conn->query("SELECT id FROM tenants ORDER BY id LIMIT 1")->fetchColumn();
$zz = 0; $teamIn = 0; $teamOut = 0;
try {
    $conn->prepare("INSERT INTO analysts (username, email, full_name, password_hash, is_active, is_admin, can_access_all_tenants)
                    VALUES ('zz-test-teamfolders', 'zz-test-teamfolders@invalid.test', 'ZZ test team folders', '!', 1, 0, 0)")->execute();
    $zz = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO analyst_modules (analyst_id, module_key, access_level) VALUES (?, 'tickets', 'write')")->execute([$zz]);
    $conn->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$zz, $tenant]);
    $conn->prepare("INSERT INTO teams (name) VALUES ('ZZ test team - member')")->execute();
    $teamIn = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO teams (name) VALUES ('ZZ test team - not a member')")->execute();
    $teamOut = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO analyst_teams (analyst_id, team_id) VALUES (?, ?)")->execute([$zz, $teamIn]);

    $mine  = ['analyst_id' => $zz,    'active_tenant_id' => $tenant, 'active_tenant_all' => false];
    $asAdm = ['analyst_id' => $admin, 'active_tenant_id' => $tenant, 'active_tenant_all' => false];

    echo "\n  Team folders\n";
    setTeamFolders($conn, null);
    $f = teamFolders($mine);
    ok('not set (today\'s behaviour): every team is listed', in_array($teamIn, $f, true) && in_array($teamOut, $f, true));

    setTeamFolders($conn, 'all');
    $f = teamFolders($mine);
    ok("'Every team': both teams listed", in_array($teamIn, $f, true) && in_array($teamOut, $f, true));

    setTeamFolders($conn, 'mine');
    $f = teamFolders($mine);
    ok("'Only the teams they belong to': their own team is listed", in_array($teamIn, $f, true));
    ok("...and a team they are not in, holding no tickets, is not", !in_array($teamOut, $f, true));
    $fa = teamFolders($asAdm);
    ok('...and an administrator still sees every team', in_array($teamIn, $fa, true) && in_array($teamOut, $fa, true));

    echo "\n  Assign lists\n";
    $d = callEndpoint('get_analysts.php', $asAdm, ['with_access' => '1']);
    $multi = isMultiTenant($conn);
    ok('with_access says whether the install has several companies', ($d['multi_company'] ?? null) === $multi);
    if ($multi) {
        $byId = array_column($d['analysts'], null, 'id');
        ok('the throwaway analyst can work in exactly their one company', ($byId[$zz]['accessible_tenant_ids'] ?? null) === [$tenant],
           json_encode($byId[$zz]['accessible_tenant_ids'] ?? null));
        $wrong = [];
        foreach ($d['analysts'] as $a) {
            if (!$a['is_active']) continue;
            $want = array_values(array_map('intval', getAccessibleTenantIds($conn, (int)$a['id'])));
            if (($a['accessible_tenant_ids'] ?? null) !== $want) $wrong[] = $a['id'];
        }
        ok('every active analyst\'s companies are exactly getAccessibleTenantIds()', !$wrong, 'differs for: ' . implode(', ', $wrong));
    }
    $plain = callEndpoint('get_analysts.php', $asAdm);
    ok('without with_access the list is unchanged (no companies added)', !array_key_exists('accessible_tenant_ids', $plain['analysts'][0] ?? []));
} finally {
    setTeamFolders($conn, $original === false ? null : (string)$original);
    if ($teamIn || $teamOut) {
        $conn->prepare("DELETE FROM analyst_teams WHERE team_id IN (?, ?)")->execute([$teamIn, $teamOut]);
        $conn->prepare("DELETE FROM teams WHERE id IN (?, ?) AND name LIKE 'ZZ test team - %'")->execute([$teamIn, $teamOut]);
    }
    if ($zz) {
        $conn->prepare("DELETE FROM analyst_teams WHERE analyst_id = ?")->execute([$zz]);
        $conn->prepare("DELETE FROM analyst_modules WHERE analyst_id = ?")->execute([$zz]);
        $conn->prepare("DELETE FROM analyst_tenant_access WHERE analyst_id = ?")->execute([$zz]);
        $conn->prepare("DELETE FROM analysts WHERE id = ? AND username = 'zz-test-teamfolders'")->execute([$zz]);
    }
}
$left = (int)$conn->query("SELECT (SELECT COUNT(*) FROM analysts WHERE username = 'zz-test-teamfolders') + (SELECT COUNT(*) FROM teams WHERE name LIKE 'ZZ test team - %')")->fetchColumn();
$now = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'ticket_team_folders'")->fetchColumn();
ok('cleanup: fixtures gone and the setting is as it was', $left === 0 && $now === $original, "{$left} left; setting " . var_export($now, true));

echo "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
