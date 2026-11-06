<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * SSO claim mapping (3.5.0) - includes/sso_claims.php.
 *
 *   php tests/sso-claim-mapping.php
 *
 * Reading a group claim the way IdPs really send it (arrays, single strings,
 * nested objects as the ID token decodes them, dotted paths, URL-named claims),
 * matching in any alphabet (SSO and LDAP), group -> team rows, applying them
 * at sign-in (teams + admin), the groups-seen list, and the companies a new
 * analyst gets - inside a transaction that is ALWAYS rolled back.
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/sso_claims.php';
require_once __DIR__ . '/../includes/services/analysts.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nSSO claim mapping (3.5.0)\n" . str_repeat('=', 70) . "\n";

echo "\n-- Reading the claim\n";
ok('an array claim', ssoClaimValues(['groups' => ['IT-Staff', 'Sales']], 'groups') === ['IT-Staff', 'Sales']);
ok('a single string', ssoClaimValues(['role' => 'IT-Staff'], 'role') === ['IT-Staff']);
// The ID token decodes top level as an array but nested JSON as stdClass.
$idToken = (array)json_decode('{"realm_access":{"roles":["itsm-staff","offline"]},"groups":["a"]}');
ok('a nested path on an ID token (stdClass inside)', ssoClaimValues($idToken, 'realm_access.roles') === ['itsm-staff', 'offline']);
ok('a claim named like a URL (dots in the name)', ssoClaimValues(['https://example.com/roles' => ['x']], 'https://example.com/roles') === ['x']);
ok('a missing claim is no groups', ssoClaimValues(['groups' => ['a']], 'roles') === [] && ssoClaimValues([], 'a.b') === []);
ok('blanks and objects inside the list are dropped', ssoClaimValues(['g' => ['a', '', ['x' => 1], ' b ']], 'g') === ['a', 'b']);
ok('an empty claim setting reads nothing', ssoClaimValues(['groups' => ['a']], '') === []);

echo "\n-- Matching: exact, ignoring case in any alphabet\n";
ok('ignores case', ssoInGroup(['IT-Staff'], 'it-staff'));
ok('...in any alphabet (accents, Greek, Cyrillic)', ssoInGroup(['Équipe-IT'], 'équipe-it') && ssoInGroup(['ΟΜΆΔΑ'], 'ομάδα') && ssoInGroup(['Команда'], 'КОМАНДА'));
ok('stray spaces do not matter', ssoInGroup([' IT-Staff '], 'IT-Staff'));
ok('no partial match', !ssoInGroup(['IT-Staff-Old'], 'IT-Staff'));
ok('blank never matches', !ssoInGroup([''], ''));
require_once __DIR__ . '/../includes/ldap.php';
ok('LDAP\'s analyst group matches any alphabet too', ldapInGroup([['cn' => 'Équipe-IT', 'dn' => 'CN=Équipe-IT,DC=x']], 'ÉQUIPE-IT'));

echo "\n-- Groups to teams, at sign-in\n";
$conn = connectToDatabase();
$conn->beginTransaction();
try {
    $actor = new ActorContext(actorId: 1, companyScope: null, source: 'api');
    $conn->prepare("INSERT INTO auth_providers (display_name, protocol, issuer_url, client_id, oidc_group_claim) VALUES ('ZZ groups', 'oidc', 'https://x.invalid', 'x', 'groups')")->execute();
    $P = (int)$conn->lastInsertId();
    $mkTeam = function (string $name) use ($conn): int { $conn->prepare("INSERT INTO teams (name) VALUES (?)")->execute([$name]); return (int)$conn->lastInsertId(); };
    $tDesk = $mkTeam('ZZ Desk'); $tAssets = $mkTeam('ZZ Assets'); $tHand = $mkTeam('ZZ By hand');
    $err = null; try { ssoSaveTeamMappings($conn, $P, [['group' => 'IT-Desk', 'team_id' => 99999999]]); } catch (ServiceError $e) { $err = $e->getMessage(); }
    ok('an unknown team is refused', $err !== null);
    $err = null; try { ssoSaveTeamMappings($conn, $P, [['group' => '', 'team_id' => $tDesk]]); } catch (ServiceError $e) { $err = $e->getMessage(); }
    ok('a row with a team but no group is refused', $err !== null);
    ssoSaveTeamMappings($conn, $P, [['group' => 'IT-Desk', 'team_id' => $tDesk], ['group' => 'it-desk', 'team_id' => $tDesk], ['group' => 'IT-Assets', 'team_id' => $tAssets], ['group' => '', 'team_id' => 0]]);
    $maps = ssoTeamMappings($conn, $P);
    ok('rows saved; a duplicate in another case and a blank row are dropped', count($maps) === 2, json_encode($maps));

    $X = AnalystsService::create($conn, $actor, ['email' => 'zzgroups@example.invalid', 'full_name' => 'ZZ Groups']);
    $conn->prepare("INSERT INTO analyst_teams (analyst_id, team_id) VALUES (?, ?)")->execute([$X, $tHand]);
    $teams = function () use ($conn, $X): array { $t = $conn->query("SELECT team_id FROM analyst_teams WHERE analyst_id = $X ORDER BY team_id")->fetchAll(PDO::FETCH_COLUMN); return array_map('intval', $t); };
    $provider = ['id' => $P, 'oidc_admin_group' => ''];
    $r = ssoApplyGroupAccess($conn, $provider, $X, ['it-desk', 'Sales']);
    ok('in IT-Desk: added to its team', in_array($tDesk, $teams(), true) && $r['added'] === [$tDesk], json_encode($r));
    ok('a team added by hand is left alone', in_array($tHand, $teams(), true));
    ssoApplyGroupAccess($conn, $provider, $X, ['IT-Assets']);
    $expect = [$tAssets, $tHand]; sort($expect);
    ok('moved groups: out of Desk, into Assets', $teams() === $expect, json_encode($teams()));
    ssoApplyGroupAccess($conn, $provider, $X, []);
    ok('in no group: out of every mapped team, the hand-added one stays', $teams() === [$tHand], json_encode($teams()));
    ok('admin left alone with no admin group', AnalystsService::access($conn, $X, false)['is_admin'] === false);

    $adminP = ['id' => $P, 'oidc_admin_group' => 'IT-Admins'];
    ssoApplyGroupAccess($conn, $adminP, $X, ['IT-ADMINS']);
    ok('in the admin group: an administrator', AnalystsService::access($conn, $X, false)['is_admin'] === true);
    ssoApplyGroupAccess($conn, $adminP, $X, []);
    ok('out of it: not an administrator', AnalystsService::access($conn, $X, false)['is_admin'] === false);
    ssoApplyGroupAccess($conn, $adminP, $X, ['IT-Admins']);
    $others = $conn->query("SELECT id FROM analysts WHERE is_admin = 1 AND is_active = 1 AND id <> $X")->fetchAll(PDO::FETCH_COLUMN);
    if ($others) $conn->exec("UPDATE analysts SET is_admin = 0 WHERE id IN (" . implode(',', array_map('intval', $others)) . ")");
    $r = ssoApplyGroupAccess($conn, $adminP, $X, ['IT-Desk']);
    ok('the last administrator keeps it; the teams still follow', AnalystsService::access($conn, $X, false)['is_admin'] === true && in_array($tDesk, $teams(), true) && $r['admin'] === null, json_encode($r));

    echo "\n-- Groups seen, and new analysts\n";
    ssoRememberGroups($conn, $P, 'sam@example.com', ['IT-Desk', 'Sales']);
    ssoRememberGroups($conn, $P, 'jo@example.com', ['IT-Desk', 'Finance']);
    $seen = ssoSeenGroups($conn, $P);
    ok('every group seen is kept once, for the dropdown', count($seen) === 3 && in_array('Finance', array_column($seen, 'group'), true), json_encode($seen));
    ssoSaveGroupLabels($conn, $P, [['group' => 'it-desk', 'label' => '  IT service desk  '], ['group' => 'Sales', 'label' => ''], ['group' => 'Never-sent', 'label' => 'Ghost']]);
    $seen = ssoSeenGroups($conn, $P);
    $byGroup = array_column($seen, 'label', 'group');
    ok('a group can be given a name (trimmed), shown first', $seen[0]['group'] === 'IT-Desk' && $seen[0]['label'] === 'IT service desk', json_encode($seen));
    ok('a blank name stays unnamed', $byGroup['Sales'] === null);
    ok('a group never sent cannot be named into the list', !array_key_exists('Never-sent', $byGroup) && count($seen) === 3);
    ok('a name is what messages show; an unnamed group shows its code', ssoGroupDisplay($seen, 'IT-DESK') === 'IT service desk' && ssoGroupDisplay($seen, 'Finance') === 'Finance');
    ssoRememberGroups($conn, $P, 'sam@example.com', ['IT-Desk']);
    ok('signing in again keeps the name', array_column(ssoSeenGroups($conn, $P), 'label', 'group')['IT-Desk'] === 'IT service desk');
    $Y = AnalystsService::create($conn, $actor, ['email' => 'zzgroups2@example.invalid']);
    $conn->exec("UPDATE analysts SET can_access_all_modules = 1 WHERE id = $Y");
    ssoJitStartForTeams($conn, $P, $Y, null);
    ok('a new analyst of a provider with teams starts with no modules of their own', (int)$conn->query("SELECT can_access_all_modules FROM analysts WHERE id = $Y")->fetchColumn() === 0);
    $conn->exec("UPDATE analysts SET can_access_all_modules = 1 WHERE id = $Y");
    ssoJitStartForTeams($conn, $P, $Y, 'tickets');
    ok('(control) unless the provider names default modules', (int)$conn->query("SELECT can_access_all_modules FROM analysts WHERE id = $Y")->fetchColumn() === 1);
    echo "\n-- Companies for analysts a provider creates (jit_company_access)\n";
    require_once __DIR__ . '/../includes/ldap.php';
    if (!isMultiTenant($conn) || !ssoJitCompanyReady($conn)) {
        echo "  SKIP  needs a multi-company install and Database Verification\n";
    } else {
        $default = getDefaultTenantId($conn);
        $client = (int)$conn->query("SELECT id FROM tenants WHERE id <> $default ORDER BY id LIMIT 1")->fetchColumn();
        $mkProvider = function (string $mode, ?int $tenant) use ($conn): int {
            $conn->prepare("INSERT INTO auth_providers (display_name, protocol, issuer_url, client_id, jit_company_access, tenant_id) VALUES ('ZZ jit', 'ldap', '', '', ?, ?)")
                 ->execute([$mode, $tenant]);
            return (int)$conn->lastInsertId();
        };
        $companies = function (int $id) use ($conn): array {
            $a = $conn->query("SELECT can_access_all_tenants FROM analysts WHERE id = $id")->fetchColumn();
            $t = $conn->query("SELECT tenant_id FROM analyst_tenant_access WHERE analyst_id = $id ORDER BY tenant_id")->fetchAll(PDO::FETCH_COLUMN);
            return [(int)$a, array_map('intval', $t)];
        };
        $n = 0;
        $make = function (int $providerId) use ($conn, &$n): int {
            $n++;
            return ldapCreateAnalyst($conn, $providerId, "zzjit$n", "ZZ Jit $n", "zzjit$n@example.invalid", null);
        };
        ok('"all" (the default): every company, as before', $companies($make($mkProvider('all', null))) === [1, []]);
        ok('"none": no companies', $companies($make($mkProvider('none', null))) === [0, []]);
        if ($client) {
            ok('"provider" on a client company\'s provider: that company only', $companies($make($mkProvider('provider', $client))) === [0, [$client]]);
        }
        ok('"provider" on a global provider: the Default company', $companies($make($mkProvider('provider', null))) === [0, [$default]]);
        $ex = AnalystsService::create($conn, $actor, ['email' => 'zzjitkeep@example.invalid']);
        $conn->exec("UPDATE analysts SET can_access_all_tenants = 1 WHERE id = $ex");
        ok('(control) only applied at creation - nobody else is touched', $companies($ex) === [1, []]);
    }
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . get_class($e) . ': ' . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
} finally {
    if ($conn->inTransaction()) $conn->rollBack();
}

echo str_repeat('-', 70) . "\n{$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
