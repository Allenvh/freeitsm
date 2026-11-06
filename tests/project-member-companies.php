<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Project members and the project's company (GH #210): an analyst, a team or a
 * project manager who cannot see the project's company is refused, people who
 * can are accepted, someone added before the rule is flagged rather than
 * dropped, and the pickers' lookups say who can see what.
 *
 * Written after a question on GH #210. Being on a project never let anyone past
 * its company - every read checks the company first - but anyone could be ADDED,
 * so a project could list people who could never open it, and its notifications
 * named it to them. A Person (from People) already had this rule.
 *
 * 🔴 Never touches the install's own database: database/freeitsm.sql is imported
 * into a throwaway zz_prj210_* database, dropped at the end, pass or fail. N=1 and
 * N=3 run in separate processes (tenantCount() is cached per process).
 *
 * Run: php tests/project-member-companies.php
 */

$ROOT = dirname(__DIR__);

// Child: run the real lookups endpoint as an analyst.   --run <db> <analystId>
if (($argv[1] ?? '') === '--run') {
    [, , $db, $aid] = $argv;
    define('DB_NAME', $db);
    ini_set('display_errors', 'stderr');
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['HTTP_HOST'] = 'localhost';
    session_id('zzprj210' . $aid);
    @session_start();
    $_SESSION = ['analyst_id' => (int)$aid, 'analyst_name' => 'ZZ'];
    session_write_close();
    chdir("$ROOT/api/projects");
    require "$ROOT/api/projects/lookups.php";
    exit;
}

$pass = 0; $fail = 0;
function ok(string $label, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; printf("  PASS %-74s\n", $label); }
    else       { $fail++; printf("  FAIL %-74s %s\n", $label, $detail); }
}
/** null when it worked, the refusal's message when it threw. */
function refused(callable $fn): ?string {
    try { $fn(); return null; } catch (Throwable $e) { return $e->getMessage(); }
}

// Child: one phase.   --phase <db> <n1|n3> <fixture base64>
if (($argv[1] ?? '') === '--phase') {
    [, , $db, $phase, $fx] = $argv;
    $fx = json_decode(base64_decode($fx), true);
    define('DB_NAME', $db);
    ini_set('display_errors', 'stderr');
    $er = error_reporting(E_ALL & ~E_WARNING);   // db_config.php's own DB_NAME, ignored on purpose
    require "$ROOT/config.php";
    error_reporting($er);
    require_once "$ROOT/includes/functions.php";
    require_once "$ROOT/includes/services/project_tools.php";
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $ctx = new ActorContext($fx['admin'], null, 'ui', 'en', 'ZZ');   // can reach every company
    $add = fn(array $in) => ProjectToolsService::addMember($conn, $ctx, $fx['prj'], $in);

    if ($phase === 'n1') {
        echo "\n1. A single-company install\n";
        ok('any analyst can be added', refused(fn() => $add(['analyst_id' => $fx['zzB']])) === null);
        ok('any team can be added', refused(fn() => $add(['team_id' => $fx['tNone']])) === null);
        ok('any analyst can be the project manager', refused(fn() => ProjectsService::updateProject($conn, $ctx, $fx['prj'], ['owner_analyst_id' => $fx['zzB']])) === null);
        $m = ProjectToolsService::members($conn, $fx['prj']);
        ok('nobody is flagged', !array_filter($m, fn($r) => !empty($r['outside_company'])));
    }

    if ($phase === 'n3') {
        echo "\n2. Members\n";
        $r = refused(fn() => $add(['analyst_id' => $fx['zzB']]));
        ok('an analyst who cannot see the company is refused', $r !== null && str_contains($r, 'cannot see'), (string)$r);
        ok('POSITIVE CONTROL: one who can is added', refused(fn() => $add(['analyst_id' => $fx['zzA']])) === null);
        ok('an every-company analyst is added', refused(fn() => $add(['analyst_id' => $fx['zzAll']])) === null);
        $r = refused(fn() => $add(['team_id' => $fx['tNone']]));
        ok('a team that gives no access to the company is refused', $r !== null && str_contains($r, 'does not give access'), (string)$r);
        $r = refused(fn() => $add(['team_id' => $fx['tB']]));
        ok('...and one that gives access to another company', $r !== null, (string)$r);
        ok('POSITIVE CONTROL: a team that gives access to it is added', refused(fn() => $add(['team_id' => $fx['tA']])) === null);
        ok('...and an every-company team', refused(fn() => $add(['team_id' => $fx['tAll']])) === null);
        $r = refused(fn() => $add(['user_id' => $fx['uB']]));
        ok('a person from another company is still refused (unchanged)', $r !== null && str_contains($r, 'different company'), (string)$r);
        ok('POSITIVE CONTROL: a person from the company is added', refused(fn() => $add(['user_id' => $fx['uA']])) === null);

        echo "\n3. The project manager\n";
        $r = refused(fn() => ProjectsService::updateProject($conn, $ctx, $fx['prj'], ['owner_analyst_id' => $fx['zzB']]));
        ok('a manager who cannot see the company is refused', $r !== null && str_contains($r, 'cannot see'), (string)$r);
        ok('POSITIVE CONTROL: one who can is accepted', refused(fn() => ProjectsService::updateProject($conn, $ctx, $fx['prj'], ['owner_analyst_id' => $fx['zzA']])) === null);
        $r = refused(fn() => ProjectsService::createProject($conn, $ctx, ['name' => 'ZZ new', 'owner_analyst_id' => $fx['zzB']], $fx['A']));
        ok('a new project with such a manager is refused', $r !== null && str_contains($r, 'cannot see'), (string)$r);
        ok('POSITIVE CONTROL: ...and with one who can, created', refused(fn() => ProjectsService::createProject($conn, $ctx, ['name' => 'ZZ new', 'owner_analyst_id' => $fx['zzA']], $fx['A'])) === null);
        ok('a project whose old manager cannot see the company can still be edited', refused(fn() => ProjectsService::updateProject($conn, $ctx, $fx['legacy'], ['name' => 'ZZ legacy renamed'])) === null);

        echo "\n4. Someone added before the rule\n";
        $m = array_column(ProjectToolsService::members($conn, $fx['legacy']), null, 'analyst_id');
        ok('is still listed, and flagged', !empty($m[$fx['zzB']]['outside_company']), json_encode($m[$fx['zzB']] ?? null));
        ok('CONTROL: a member who can see the company is not flagged', isset($m[$fx['zzA']]) && empty($m[$fx['zzA']]['outside_company']));
        $t = array_column(ProjectToolsService::members($conn, $fx['legacy']), null, 'team_id');
        ok('a team that gives no access is flagged too', !empty($t[$fx['tNone']]['outside_company']));

        echo "\n5. The pickers' lookups\n";
        $out = shell_exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . " --run $db " . $fx['admin'] . ' 2>' . (PHP_OS_FAMILY === 'Windows' ? 'NUL' : '/dev/null'));
        $L = json_decode(substr((string)$out, (int)strpos((string)$out, '{')), true) ?: [];
        $ac = $L['analyst_companies'] ?? null; $tc = $L['team_companies'] ?? null;
        ok('analyst_companies: the A analyst reaches A, the B analyst does not', is_array($ac) && in_array($fx['A'], $ac[$fx['zzA']] ?? []) && !in_array($fx['A'], $ac[$fx['zzB']] ?? []), substr((string)$out, 0, 200));
        ok('team_companies: the A team reaches A, the no-access team reaches nothing', is_array($tc) && in_array($fx['A'], $tc[$fx['tA']] ?? []) && ($tc[$fx['tNone']] ?? ['x']) === []);
        ok('...and the every-company team reaches A', in_array($fx['A'], $tc[$fx['tAll']] ?? []));
    }
    printf("\n__RESULT__ %d %d\n", $pass, $fail);
    exit(0);
}

// Parent: build, seed, run, drop.
require "$ROOT/config.php";
$mysql = getenv('MYSQL_BIN') ?: (glob('C:/wamp64/bin/mysql/*/bin/mysql.exe')[0] ?? 'mysql');
$db = 'zz_prj210_' . getmypid();
echo "\nProject members and companies (GH #210)\n" . str_repeat('=', 80) . "\nScratch database: $db\n";
$admin = new PDO('mysql:host=' . DB_SERVER . ';charset=utf8mb4', DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$admin->exec("CREATE DATABASE `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$total = [0, 0];
try {
    putenv('MYSQL_PWD=' . DB_PASSWORD);   // never on a command line
    $out = shell_exec(sprintf('"%s" -u %s -h %s %s < "%s/database/freeitsm.sql" 2>&1', $mysql, escapeshellarg(DB_USERNAME), escapeshellarg(DB_SERVER), escapeshellarg($db), $ROOT));
    if (trim((string)$out) !== '') echo "import said: " . substr(trim($out), 0, 300) . "\n";
    $c = new PDO('mysql:host=' . DB_SERVER . ";dbname=$db;charset=utf8mb4", DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $id = fn() => (int)$c->lastInsertId();
    $D = (int)$c->query("SELECT id FROM tenants WHERE is_default = 1 LIMIT 1")->fetchColumn();
    $analyst = function (string $u, int $all) use ($c, $id): int {
        $c->prepare("INSERT INTO analysts (username, password_hash, full_name, email, is_active, is_admin, can_access_all_tenants, can_access_all_modules) VALUES (?, '!', ?, ?, 1, 0, ?, 1)")
          ->execute([$u, $u, "$u@zz.invalid", $all]);
        return $id();
    };
    $team = function (string $n, int $all) use ($c, $id): int {
        $c->prepare("INSERT INTO teams (name, display_order, can_access_all_tenants) VALUES (?, 1, ?)")->execute([$n, $all]);
        return $id();
    };
    $adminId = (int)$c->query("SELECT id FROM analysts WHERE is_admin = 1 ORDER BY id LIMIT 1")->fetchColumn();
    $c->prepare("UPDATE analysts SET can_access_all_tenants = 1 WHERE id = ?")->execute([$adminId]);
    $fx = ['admin' => $adminId, 'zzA' => $analyst('zz-a', 0), 'zzB' => $analyst('zz-b', 0), 'zzAll' => $analyst('zz-all', 1),
           'tA' => $team('ZZ team A', 0), 'tB' => $team('ZZ team B', 0), 'tNone' => $team('ZZ team none', 0), 'tAll' => $team('ZZ team all', 1)];
    $project = function (string $name, ?int $tenant, ?int $owner) use ($c, $id): int {
        $c->prepare("INSERT INTO projects (name, status, methodology, tenant_id, owner_analyst_id, created_datetime) VALUES (?, 'active', 'simple', ?, ?, UTC_TIMESTAMP())")
          ->execute([$name, $tenant, $owner]);
        return $id();
    };
    $fx['prj'] = $project('ZZ project', null, null);

    $run = function (string $phase) use ($db, &$fx, &$total) {
        $out = shell_exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . " --phase $db $phase " . base64_encode(json_encode($fx)) . ' 2>&1');
        echo preg_replace('/^__RESULT__.*$/m', '', (string)$out);
        if (preg_match('/__RESULT__ (\d+) (\d+)/', (string)$out, $m)) { $total[0] += (int)$m[1]; $total[1] += (int)$m[2]; }
        else { $total[1]++; echo "  FAIL phase $phase did not finish\n"; }
    };
    $run('n1');

    // ---- three companies ----
    $c->exec("DELETE FROM project_members");
    $c->prepare("INSERT INTO tenants (name, is_default, is_active) VALUES ('ZZ Acme', 0, 1)")->execute(); $A = $id();
    $c->prepare("INSERT INTO tenants (name, is_default, is_active) VALUES ('ZZ Globex', 0, 1)")->execute(); $B = $id();
    $c->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?), (?, ?)")->execute([$fx['zzA'], $A, $fx['zzB'], $B]);
    $c->prepare("INSERT INTO team_tenant_access (team_id, tenant_id) VALUES (?, ?), (?, ?)")->execute([$fx['tA'], $A, $fx['tB'], $B]);
    $c->prepare("UPDATE projects SET tenant_id = ?, owner_analyst_id = NULL WHERE id = ?")->execute([$A, $fx['prj']]);
    $c->prepare("INSERT INTO users (email, display_name, tenant_id, created_at) VALUES ('zz-a@zz.invalid', 'ZZ A person', ?, UTC_TIMESTAMP())")->execute([$A]); $fx['uA'] = $id();
    $c->prepare("INSERT INTO users (email, display_name, tenant_id, created_at) VALUES ('zz-b@zz.invalid', 'ZZ B person', ?, UTC_TIMESTAMP())")->execute([$B]); $fx['uB'] = $id();
    // A project from before the rule: a manager and members who cannot see its company.
    $fx['legacy'] = $project('ZZ legacy', $A, $fx['zzB']);
    $c->prepare("INSERT INTO project_members (project_id, analyst_id, position, created_datetime) VALUES (?, ?, 1, UTC_TIMESTAMP()), (?, ?, 2, UTC_TIMESTAMP())")
      ->execute([$fx['legacy'], $fx['zzB'], $fx['legacy'], $fx['zzA']]);
    $c->prepare("INSERT INTO project_members (project_id, team_id, position, created_datetime) VALUES (?, ?, 3, UTC_TIMESTAMP())")->execute([$fx['legacy'], $fx['tNone']]);
    $fx['A'] = $A; $fx['B'] = $B;
    $run('n3');
} finally {
    $admin->exec("DROP DATABASE IF EXISTS `$db`");
    foreach (glob(rtrim(preg_replace('/^\d+;/', '', session_save_path() ?: sys_get_temp_dir()), '/\\') . '/sess_zzprj210*') ?: [] as $f) @unlink($f);
}
printf("\n%d passed, %d failed\n", $total[0], $total[1]);
exit($total[1] ? 1 : 0);
