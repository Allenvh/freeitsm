<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Workflows and companies (GH #209): which workflows run for which company's
 * events, that a company's own workflow can only act on that company's records,
 * who may see, change and fire which workflow, and that the run log - which
 * holds each event's payload - follows the event's company. In the app's
 * endpoints, the REST API and the engine.
 *
 * Written when Workflow became company-aware. Until then there was no company
 * boundary in the module at all: every workflow ran for every company, the run
 * log showed every company's payloads to anyone with Workflow access (and to a
 * company-scoped API key), and an action ran wherever its ticket id pointed.
 *
 * 🔴 Never touches the install's own database. It imports database/freeitsm.sql
 * into a throwaway database (zz_wf209_*), and drops it at the end, pass or fail.
 * Each phase runs in its own process: tenantCount() is cached per process, so
 * N=1 and N=2 in one process would test the first answer twice. Every endpoint
 * call is its own process too, because endpoints exit.
 *
 *   1. N=1  - a single-company install: nothing changes.
 *   2. N=2  - the boundary, with positive controls beside every refusal.
 *   3. Before Database Verification - the columns not there yet, on a
 *            multi-company install: the old behaviour, nothing breaks.
 *
 * Needs the mysql client WAMP ships (or MYSQL_BIN) and rights to create a database.
 *
 * Run: php tests/workflow-company-scope.php
 */

$ROOT = dirname(__DIR__);

// ---------------------------------------------------------------------------
// Child mode 1: run ONE real app endpoint as an analyst against the scratch DB.
//   --run <db> <analystId> <activeTenant|0> <script> <querystring>   body on STDIN
// ---------------------------------------------------------------------------
if (($argv[1] ?? '') === '--run') {
    [, , $db, $aid, $active, $script, $query] = $argv + [6 => ''];
    define('DB_NAME', $db);               // before config.php, so this one wins
    ini_set('display_errors', 'stderr');  // the "already defined" warning must not reach the JSON
    $body = stream_get_contents(STDIN);
    // php://input is empty under the CLI: hand the endpoint the piped body.
    class ZzInput {
        public static string $data = '';
        private int $pos = 0;
        public $context;
        public function stream_open($path, $m, $o, &$p): bool { return $path === 'php://input'; }
        public function stream_read(int $n): string { $c = substr(self::$data, $this->pos, $n); $this->pos += strlen($c); return $c; }
        public function stream_eof(): bool { return $this->pos >= strlen(self::$data); }
        public function stream_stat() { return ['size' => strlen(self::$data)]; }
    }
    ZzInput::$data = $body;
    stream_wrapper_unregister('php');
    stream_wrapper_register('php', 'ZzInput');
    parse_str(base64_decode($query), $_GET);
    $_SERVER['REQUEST_METHOD'] = $body !== '' ? 'POST' : 'GET';
    $_SERVER['SCRIPT_FILENAME'] = "$ROOT/$script";
    $_SERVER['SCRIPT_NAME'] = '/freeitsm-app/' . $script;
    $_SERVER['HTTP_HOST'] = 'localhost';
    // The session first: the endpoint's own session_start() would otherwise start empty.
    session_id('zzwf209' . $aid . 'x' . $active);
    @session_start();
    $_SESSION = ['analyst_id' => (int)$aid, 'analyst_name' => 'ZZ'];
    if ((int)$active > 0) $_SESSION['active_tenant_id'] = (int)$active;
    session_write_close();
    chdir(dirname("$ROOT/$script"));
    require "$ROOT/$script";
    exit;
}

// ---------------------------------------------------------------------------
// Child mode 2: call ONE REST handler with a synthetic key.
//   --rest <db> <scope json|null> <analystId> <handler> <params json> <querystring>   body on STDIN
// ---------------------------------------------------------------------------
if (($argv[1] ?? '') === '--rest') {
    [, , $db, $scope, $aid, $fn, $params, $query] = $argv + [7 => ''];
    define('DB_NAME', $db);
    ini_set('display_errors', 'stderr');
    $_SERVER['REQUEST_METHOD'] = 'GET';
    parse_str(base64_decode($query), $_GET);
    require "$ROOT/api/v1/lib/bootstrap.php";
    require "$ROOT/api/v1/resources/workflows.php";
    $body = json_decode(stream_get_contents(STDIN), true) ?: [];
    $key = ['id' => 0, 'analyst_id' => (int)$aid, 'company_scope' => json_decode(base64_decode($scope), true)];
    $fn(connectToDatabase(), $key, json_decode(base64_decode($params), true) ?: [], $body);
    exit;
}

// ---------------------------------------------------------------------------
// Child mode 3: one phase of assertions.   --phase <db> <n1|n2|nocol> <fixture json>
// ---------------------------------------------------------------------------
$pass = 0; $fail = 0;
function ok(string $label, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; printf("  PASS %-74s\n", $label); }
    else       { $fail++; printf("  FAIL %-74s %s\n", $label, $detail); }
}
/** Run a real app endpoint; returns the decoded JSON (or ['raw' => ...]). */
function call(string $db, int $aid, int $active, string $script, array $get = [], ?array $body = null): array {
    global $ROOT;
    $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . ' --run ' . escapeshellarg($db) . " $aid $active "
         . escapeshellarg($script) . ' ' . base64_encode(http_build_query($get));
    return runChild($cmd, $body === null ? '' : json_encode($body));
}
/** Call a REST handler as a key with this company scope (null = every company). */
function rest(string $db, ?array $scope, int $aid, string $fn, array $params = [], array $get = [], ?array $body = null): array {
    $cmd = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . ' --rest ' . escapeshellarg($db) . ' '
         . base64_encode(json_encode($scope)) . " $aid $fn " . base64_encode(json_encode($params)) . ' ' . base64_encode(http_build_query($get));
    return runChild($cmd, $body === null ? '' : json_encode($body));
}
function runChild(string $cmd, string $stdin): array {
    $p = proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    fwrite($pipes[0], $stdin); fclose($pipes[0]);
    $out = stream_get_contents($pipes[1]); $err = stream_get_contents($pipes[2]);
    fclose($pipes[1]); fclose($pipes[2]); proc_close($p);
    $start = strpos($out, '{');
    $j = $start === false ? null : json_decode(substr($out, $start), true);
    return is_array($j) ? $j : ['raw' => substr($out . ' | ' . $err, 0, 400)];
}

if (($argv[1] ?? '') === '--phase') {
    [, , $db, $phase, $fx] = $argv;
    $fx = json_decode(base64_decode($fx), true);
    define('DB_NAME', $db);
    ini_set('display_errors', 'stderr');
    $er = error_reporting(E_ALL & ~E_WARNING);   // db_config.php's own DB_NAME, ignored on purpose
    require "$ROOT/config.php";
    error_reporting($er);
    require_once "$ROOT/includes/functions.php";
    require_once "$ROOT/workflow/includes/engine.php";
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $runsOf = function (int $wfId) use ($conn): array {
        $s = $conn->prepare("SELECT * FROM workflow_executions WHERE workflow_id = ? ORDER BY id");
        $s->execute([$wfId]);
        return $s->fetchAll(PDO::FETCH_ASSOC);
    };
    $lastRun = function (int $wfId) use ($runsOf): array { $r = $runsOf($wfId); return $r ? end($r) : []; };
    $col = function (string $sql, array $a = []) use ($conn) { $s = $conn->prepare($sql); $s->execute($a); return $s->fetchColumn(); };
    $A = $fx['A'] ?? 0; $B = $fx['B'] ?? 0; $D = $fx['D'];
    $tA = $fx['tA']; $tB = $fx['tB'];
    $w = $fx['w'];

    if ($phase === 'n1') {
        echo "\n1. A single-company install\n";
        ok('workflowTenancyActive() is off', !workflowTenancyActive($conn));
        WorkflowEngine::dispatch('ticket.created', ['ticket' => ['id' => $tA]]);
        ok('the shared workflow ran', count($runsOf($w['shared'])) === 1);
        ok('so did every other workflow on that trigger', count($runsOf($w['pinA'])) === 1 && count($runsOf($w['pinB'])) === 1);
        ok('no company on the payload', !str_contains((string)($lastRun($w['shared'])['trigger_payload'] ?? ''), '"company"'));
        ok('no company.id condition field offered', !in_array('company.id', WorkflowEngine::availableFields('ticket.created'), true));
        $l = call($db, $fx['zzall'], 0, 'api/workflow/list.php');
        ok('list: every workflow, multi_company false', ($l['multi_company'] ?? null) === false && count($l['workflows'] ?? []) >= 3, json_encode($l)[0] ?? '');
        $g = call($db, $fx['zza'], 0, 'api/workflow/get.php', ['id' => $w['shared']]);
        ok('get: can_edit for anyone with Workflow access', ($g['workflow']['can_edit'] ?? null) === true);
        $s = call($db, $fx['zza'], 0, 'api/workflow/save.php', [], ['id' => $w['shared'], 'name' => 'ZZ shared', 'trigger_event' => 'ticket.created', 'conditions' => [], 'actions' => [], 'is_active' => 1]);
        ok('save: works as before', ($s['success'] ?? false) === true, json_encode($s));
        $r = rest($db, null, $fx['zzall'], 'apiWorkflowsGet', [$w['shared']]);
        ok('REST: company is null', array_key_exists('company', $r['data'] ?? []) && $r['data']['company'] === null, json_encode($r));
    }

    if ($phase === 'n2') {
        echo "\n2a. Which workflows run\n";
        ok('workflowTenancyActive() is on', workflowTenancyActive($conn));
        ok('the event company of A\'s ticket is A', workflowEventTenantId($conn, ['ticket' => ['id' => $tA]]) === $A);
        ok('a ticket with no company belongs to Default', workflowEventTenantId($conn, ['ticket' => ['id' => $fx['tD']]]) === $D);
        ok('a supplier event belongs to no company', workflowEventTenantId($conn, ['supplier' => ['id' => 1]]) === null);
        WorkflowEngine::dispatch('ticket.created', ['ticket' => ['id' => $tA]]);
        ok('A\'s ticket runs the shared workflow', count($runsOf($w['shared'])) === 1);
        ok('POSITIVE CONTROL: ...and A\'s own', count($runsOf($w['pinA'])) === 1);
        ok('...but not B\'s', count($runsOf($w['pinB'])) === 0);
        ok('the run is filed under A', (int)($lastRun($w['shared'])['tenant_id'] ?? 0) === $A);
        $pl = json_decode($lastRun($w['shared'])['trigger_payload'] ?? '{}', true);
        ok('{{company.name}} is on the payload', ($pl['company']['name'] ?? '') === 'ZZ Acme', json_encode($pl['company'] ?? null));
        ok('company.id is offered as a condition field', in_array('company.id', WorkflowEngine::availableFields('ticket.created'), true));
        WorkflowEngine::dispatch('supplier.created', ['supplier' => ['id' => 1, 'name' => 'ZZ']]);
        ok('an event with no company runs the shared workflow', count($runsOf($w['supShared'])) === 1);
        ok('...not a company\'s', count($runsOf($w['supA'])) === 0);
        $sr = $lastRun($w['supShared']);
        ok('...and its run has no company', array_key_exists('tenant_id', $sr) && $sr['tenant_id'] === null, json_encode($sr['tenant_id'] ?? 'missing'));

        echo "\n2b. A company's workflow acts only on its own records\n";
        $before = (int)$col("SELECT priority_id FROM tickets WHERE id = ?", [$tB]);
        $r = WorkflowEngine::manualFire($w['crossA'], ['ticket' => ['id' => $tA]]);
        ok('A\'s workflow naming B\'s ticket fails', $r['status'] === 'failed' && str_contains((string)$r['error_message'], 'belongs to another company'), (string)$r['error_message']);
        ok('...and B\'s ticket is untouched', (int)$col("SELECT priority_id FROM tickets WHERE id = ?", [$tB]) === $before);
        $r = WorkflowEngine::manualFire($w['ownA'], ['ticket' => ['id' => $tA]]);
        ok('POSITIVE CONTROL: A\'s workflow on A\'s ticket works', $r['status'] === 'success', (string)$r['error_message']);
        ok('...and changed it', (int)$col("SELECT priority_id FROM tickets WHERE id = ?", [$tA]) === $fx['prio2']);
        $r = WorkflowEngine::manualFire($w['crossShared'], ['ticket' => ['id' => $tA]]);
        ok('a SHARED workflow naming B\'s ticket still works (unchanged)', $r['status'] === 'success', (string)$r['error_message']);
        $r = WorkflowEngine::manualFire($w['assignB'], ['ticket' => ['id' => $tA]]);
        ok('A\'s workflow cannot assign A\'s ticket to an analyst who cannot see A', $r['status'] === 'failed' && str_contains((string)$r['error_message'], 'cannot see'), (string)$r['error_message']);
        $r = WorkflowEngine::manualFire($w['assignA'], ['ticket' => ['id' => $tA]]);
        ok('POSITIVE CONTROL: ...but can to one who can', $r['status'] === 'success', (string)$r['error_message']);
        $r = WorkflowEngine::manualFire($w['taskA'], []);
        $task = (int)($r['step_log'][0]['result']['task_id'] ?? 0);
        ok('a task from A\'s workflow is A\'s', $task > 0 && (int)$col("SELECT tenant_id FROM tasks WHERE id = ?", [$task]) === $A, json_encode($r['step_log']));
        $r = WorkflowEngine::manualFire($w['taskShared'], ['ticket' => ['id' => $tB]]);
        $task = (int)($r['step_log'][0]['result']['task_id'] ?? 0);
        ok('a task a shared workflow links to B\'s ticket is B\'s (it was always Default)', $task > 0 && (int)$col("SELECT tenant_id FROM tasks WHERE id = ?", [$task]) === $B, json_encode($r['step_log']));

        echo "\n2c. The three new actions\n";
        $r = WorkflowEngine::manualFire($w['teamA'], ['ticket' => ['id' => $tA]]);
        ok('Assign ticket to team', $r['status'] === 'success' && (int)$col("SELECT assigned_team_id FROM tickets WHERE id = ?", [$tA]) === $fx['team'], (string)$r['error_message']);
        ok('...audited', (int)$col("SELECT COUNT(*) FROM ticket_audit WHERE ticket_id = ? AND analyst_id IS NULL", [$tA]) > 0);
        $r = WorkflowEngine::manualFire($w['deptA'], ['ticket' => ['id' => $tA]]);
        ok('Set ticket department', $r['status'] === 'success' && (int)$col("SELECT department_id FROM tickets WHERE id = ?", [$tA]) === $fx['dept'], (string)$r['error_message']);
        $r = WorkflowEngine::manualFire($w['catShared'], ['ticket' => ['id' => $tA]]);
        ok('Set ticket category: a shared category', $r['status'] === 'success' && (int)$col("SELECT category_id FROM tickets WHERE id = ?", [$tA]) === $fx['catShared'], (string)$r['error_message']);
        $r = WorkflowEngine::manualFire($w['catB'], ['ticket' => ['id' => $tA]]);
        ok('...B\'s own category on A\'s ticket fails, even from a SHARED workflow', $r['status'] === 'failed' && str_contains((string)$r['error_message'], 'not one this ticket can have'), (string)$r['error_message']);
        ok('...and the category is unchanged', (int)$col("SELECT category_id FROM tickets WHERE id = ?", [$tA]) === $fx['catShared']);
        $r = WorkflowEngine::manualFire($w['teamCross'], ['ticket' => ['id' => $tA]]);
        ok('A\'s workflow cannot set B\'s ticket\'s team', $r['status'] === 'failed' && str_contains((string)$r['error_message'], 'belongs to another company'), (string)$r['error_message']);

        echo "\n2d. The app: who sees and changes what\n";
        $l = call($db, $fx['zza'], $A, 'api/workflow/list.php');
        $ids = array_column($l['workflows'] ?? [], 'id');
        ok('list as A: shared and A\'s', in_array($w['shared'], $ids) && in_array($w['pinA'], $ids), json_encode($ids));
        ok('...not B\'s', !in_array($w['pinB'], $ids));
        $row = array_values(array_filter($l['workflows'] ?? [], fn($x) => $x['id'] == $w['shared']))[0] ?? [];
        ok('...the shared one is read-only for A, A\'s own is not', ($row['can_edit'] ?? null) === false
            && (array_values(array_filter($l['workflows'], fn($x) => $x['id'] == $w['pinA']))[0]['can_edit'] ?? null) === true);
        $g = call($db, $fx['zza'], $A, 'api/workflow/get.php', ['id' => $w['pinB']]);
        ok('get B\'s as A: not found', ($g['success'] ?? true) === false && ($g['error'] ?? '') === 'Workflow not found', json_encode($g));
        $g = call($db, $fx['zza'], $A, 'api/workflow/get.php', ['id' => $w['hookShared']]);
        ok('get the shared one as A: readable, secrets hidden', ($g['workflow']['actions'][0]['args']['secret'] ?? '') === '(hidden)', json_encode($g['workflow']['actions'] ?? $g));
        $g = call($db, $fx['zzall'], $D, 'api/workflow/get.php', ['id' => $w['hookShared']]);
        ok('POSITIVE CONTROL: ...the every-company analyst sees the secret', ($g['workflow']['actions'][0]['args']['secret'] ?? '') === 'zz-secret', json_encode($g['workflow']['actions'] ?? $g));
        $base = ['name' => 'ZZ new', 'trigger_event' => 'ticket.created', 'conditions' => [], 'actions' => [], 'is_active' => 0];
        $s = call($db, $fx['zza'], $A, 'api/workflow/save.php', [], $base + ['company_id' => null]);
        ok('A cannot make a shared workflow', ($s['success'] ?? true) === false, json_encode($s));
        $s = call($db, $fx['zza'], $A, 'api/workflow/save.php', [], $base + ['company_id' => $B]);
        ok('...nor one for B', ($s['success'] ?? true) === false, json_encode($s));
        $s = call($db, $fx['zza'], $A, 'api/workflow/save.php', [], $base + ['company_id' => $A]);
        $newA = (int)($s['id'] ?? 0);
        ok('POSITIVE CONTROL: ...but can for A', ($s['success'] ?? false) === true && (int)$col("SELECT tenant_id FROM workflows WHERE id = ?", [$newA]) === $A, json_encode($s));
        $s = call($db, $fx['zza'], $A, 'api/workflow/save.php', [], $base);
        ok('no company sent: A\'s new workflow lands in A', ($s['success'] ?? false) && (int)$col("SELECT tenant_id FROM workflows WHERE id = ?", [(int)$s['id']]) === $A, json_encode($s));
        $s = call($db, $fx['zza'], $A, 'api/workflow/save.php', [], ['id' => $newA, 'company_id' => $B] + $base);
        ok('A cannot move its workflow to B', ($s['success'] ?? true) === false && (int)$col("SELECT tenant_id FROM workflows WHERE id = ?", [$newA]) === $A, json_encode($s));
        $s = call($db, $fx['zza'], $A, 'api/workflow/save.php', [], ['id' => $w['shared']] + $base);
        ok('A cannot change the shared workflow', ($s['success'] ?? true) === false && $col("SELECT name FROM workflows WHERE id = ?", [$w['shared']]) !== 'ZZ new', json_encode($s));
        $s = call($db, $fx['zzall'], $D, 'api/workflow/save.php', [], $base);
        ok('no company sent: the every-company analyst\'s new workflow is shared', ($s['success'] ?? false) && $col("SELECT tenant_id FROM workflows WHERE id = ?", [(int)$s['id']]) === null, json_encode($s));
        $d = call($db, $fx['zza'], $A, 'api/workflow/delete.php', [], ['id' => $w['shared']]);
        ok('A cannot delete the shared workflow', ($d['success'] ?? true) === false && (int)$col("SELECT COUNT(*) FROM workflows WHERE id = ?", [$w['shared']]) === 1, json_encode($d));
        $d = call($db, $fx['zza'], $A, 'api/workflow/delete.php', [], ['id' => $w['pinB']]);
        ok('...nor B\'s', ($d['success'] ?? true) === false && (int)$col("SELECT COUNT(*) FROM workflows WHERE id = ?", [$w['pinB']]) === 1, json_encode($d));
        $d = call($db, $fx['zza'], $A, 'api/workflow/delete.php', [], ['id' => $newA]);
        ok('POSITIVE CONTROL: ...but can delete its own', ($d['success'] ?? false) === true, json_encode($d));
        $f = call($db, $fx['zza'], $A, 'api/workflow/fire.php', [], ['id' => $w['crossShared'], 'dry_run' => true]);
        ok('A cannot fire the shared workflow', ($f['success'] ?? true) === false, json_encode($f));
        $f = call($db, $fx['zza'], $A, 'api/workflow/fire.php', [], ['id' => $w['pinA'], 'dry_run' => true]);
        ok('POSITIVE CONTROL: ...but can fire its own', ($f['success'] ?? false) === true, json_encode($f));

        echo "\n2e. The run log\n";
        WorkflowEngine::dispatch('ticket.created', ['ticket' => ['id' => $tB]]);
        $bRun = (int)$col("SELECT id FROM workflow_executions WHERE workflow_id = ? AND tenant_id = ? ORDER BY id DESC LIMIT 1", [$w['shared'], $B]);
        $aRun = (int)$col("SELECT id FROM workflow_executions WHERE workflow_id = ? AND tenant_id = ? ORDER BY id DESC LIMIT 1", [$w['shared'], $A]);
        $nullRun = (int)$col("SELECT id FROM workflow_executions WHERE workflow_id = ? ORDER BY id DESC LIMIT 1", [$w['supShared']]);
        $e = call($db, $fx['zza'], $A, 'api/workflow/executions.php', ['per_page' => 100]);
        $eids = array_column($e['executions'] ?? [], 'id');
        ok('A\'s run log has A\'s run', in_array($aRun, $eids), json_encode($eids));
        ok('...not B\'s run of the same shared workflow', !in_array($bRun, $eids));
        ok('...nor a run with no company', !in_array($nullRun, $eids));
        ok('...and the tallies count only what A can see', array_sum($e['tally'] ?? []) === (int)($e['total'] ?? -1)
            || array_sum($e['tally'] ?? []) <= (int)$col("SELECT COUNT(*) FROM workflow_executions WHERE tenant_id = ?", [$A]), json_encode($e['tally'] ?? null));
        $x = call($db, $fx['zza'], $A, 'api/workflow/execution_detail.php', ['id' => $bRun]);
        ok('B\'s run by id, as A: not found', ($x['success'] ?? true) === false, json_encode($x));
        $x = call($db, $fx['zza'], $A, 'api/workflow/execution_detail.php', ['id' => $aRun]);
        ok('POSITIVE CONTROL: A\'s run by id, as A', ($x['success'] ?? false) === true, json_encode($x));
        $x = call($db, $fx['zzall'], $D, 'api/workflow/execution_detail.php', ['id' => $nullRun]);
        ok('a run with no company: the every-company analyst can read it', ($x['success'] ?? false) === true, json_encode($x));
        $g = call($db, $fx['zza'], $A, 'api/workflow/get.php', ['id' => $w['shared']]);
        $gids = array_column($g['executions'] ?? [], 'id');
        ok('the editor\'s Recent runs for A: A\'s, not B\'s', in_array($aRun, $gids) && !in_array($bRun, $gids), json_encode($gids));

        echo "\n2f. REST\n";
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsList');
        $rids = array_column($r['data'] ?? [], 'id');
        ok('a key scoped to A lists shared and A\'s, not B\'s', in_array($w['shared'], $rids) && in_array($w['pinA'], $rids) && !in_array($w['pinB'], $rids), json_encode($rids));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsList', [], ['company' => 'shared']);
        ok('?company=shared lists only shared ones', ($r['data'] ?? []) && !array_filter($r['data'], fn($x) => $x['company'] !== null), json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsList', [], ['company' => $B]);
        ok('?company=<B> with an A key: forbidden', ($r['error']['code'] ?? '') === 'forbidden', json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsGet', [$w['pinB']]);
        ok('GET B\'s with an A key: not found', ($r['error']['code'] ?? '') === 'not_found', json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsGet', [$w['pinA']]);
        ok('POSITIVE CONTROL: GET A\'s, with its company', ($r['data']['company']['id'] ?? 0) === $A, json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsCreate', [], [], ['name' => 'ZZ rest', 'trigger_event' => 'ticket.created']);
        ok('POST with an A key and no company: lands in A', ($r['data']['company']['id'] ?? 0) === $A, json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsCreate', [], [], ['name' => 'ZZ rest', 'trigger_event' => 'ticket.created', 'company_id' => null]);
        ok('POST shared with an A key: forbidden', ($r['error']['code'] ?? '') === 'forbidden', json_encode($r));
        $r = rest($db, null, $fx['zzall'], 'apiWorkflowsCreate', [], [], ['name' => 'ZZ rest all', 'trigger_event' => 'ticket.created']);
        ok('POST with an every-company key and no company: shared (as before)', array_key_exists('company', $r['data'] ?? []) && $r['data']['company'] === null, json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsUpdate', [$w['shared']], [], ['name' => 'ZZ hacked']);
        ok('PATCH the shared one with an A key: forbidden', ($r['error']['code'] ?? '') === 'forbidden', json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsUpdate', [$w['pinA']], [], ['company_id' => $B]);
        ok('PATCH A\'s to B with an A key: forbidden', ($r['error']['code'] ?? '') === 'forbidden', json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsFire', [$w['crossShared']], [], ['payload' => []]);
        ok('fire the shared one with an A key: forbidden', ($r['error']['code'] ?? '') === 'forbidden', json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsDelete', [$w['pinB']]);
        ok('DELETE B\'s with an A key: not found', ($r['error']['code'] ?? '') === 'not_found', json_encode($r));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowExecutionsListAll', [], ['per_page' => 100]);
        $rx = array_column($r['data'] ?? [], 'id');
        ok('runs with an A key: A\'s, not B\'s, not no-company', in_array($aRun, $rx) && !in_array($bRun, $rx) && !in_array($nullRun, $rx), json_encode($rx));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowExecutionsGet', [$bRun]);
        ok('B\'s run by id with an A key: not found', ($r['error']['code'] ?? '') === 'not_found', json_encode($r));
        $r = rest($db, null, $fx['zzall'], 'apiWorkflowExecutionsGet', [$bRun]);
        ok('POSITIVE CONTROL: ...with an every-company key, with its company', ($r['data']['company']['id'] ?? 0) === $B, json_encode($r));
    }

    if ($phase === 'nocol') {
        echo "\n3. Before Database Verification (columns not there) on a multi-company install\n";
        ok('workflowTenancyReady() is off', !workflowTenancyReady($conn));
        WorkflowEngine::dispatch('ticket.created', ['ticket' => ['id' => $tA]]);
        ok('dispatch still runs every workflow, as before', count($runsOf($w['pinB'])) >= 1 && count($runsOf($w['shared'])) >= 1);
        $r = WorkflowEngine::manualFire($w['crossA'], ['ticket' => ['id' => $tA]]);
        ok('no company guard yet (the old behaviour)', $r['status'] === 'success', (string)$r['error_message']);
        $l = call($db, $fx['zza'], $A, 'api/workflow/list.php');
        ok('list works', ($l['success'] ?? false) === true && count($l['workflows'] ?? []) >= 3, json_encode($l));
        $g = call($db, $fx['zza'], $A, 'api/workflow/get.php', ['id' => $w['pinB']]);
        ok('get works', ($g['success'] ?? false) === true, json_encode($g));
        $s = call($db, $fx['zza'], $A, 'api/workflow/save.php', [], ['name' => 'ZZ nocol', 'trigger_event' => 'ticket.created', 'conditions' => [], 'actions' => [], 'is_active' => 0, 'company_id' => $A]);
        ok('save works', ($s['success'] ?? false) === true, json_encode($s));
        $e = call($db, $fx['zza'], $A, 'api/workflow/executions.php');
        ok('the run log works', ($e['success'] ?? false) === true, json_encode($e));
        $r = rest($db, [$A], $fx['zza'], 'apiWorkflowsList');
        ok('REST list works', isset($r['data']), json_encode($r));
    }
    printf("\n__RESULT__ %d %d\n", $pass, $fail);
    exit(0);
}

// ---------------------------------------------------------------------------
// Parent: build the scratch database, seed it, run the phases, drop it.
// ---------------------------------------------------------------------------
require "$ROOT/config.php";
$mysql = getenv('MYSQL_BIN') ?: (glob('C:/wamp64/bin/mysql/*/bin/mysql.exe')[0] ?? 'mysql');
$db = 'zz_wf209_' . getmypid();
echo "\nWorkflows and companies (GH #209)\n" . str_repeat('=', 80) . "\nScratch database: $db\n";

$admin = new PDO('mysql:host=' . DB_SERVER . ';charset=utf8mb4', DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
$admin->exec("CREATE DATABASE `$db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$total = [0, 0];
try {
    putenv('MYSQL_PWD=' . DB_PASSWORD);   // never on a command line
    $cmd = sprintf('"%s" -u %s -h %s %s < "%s/database/freeitsm.sql" 2>&1', $mysql, escapeshellarg(DB_USERNAME), escapeshellarg(DB_SERVER), escapeshellarg($db), $ROOT);
    $out = shell_exec($cmd);
    if (trim((string)$out) !== '') echo "import said: " . substr(trim($out), 0, 300) . "\n";
    $c = new PDO('mysql:host=' . DB_SERVER . ";dbname=$db;charset=utf8mb4", DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $id = fn() => (int)$c->lastInsertId();
    $D = (int)$c->query("SELECT id FROM tenants WHERE is_default = 1 LIMIT 1")->fetchColumn();
    $status = (int)$c->query("SELECT id FROM ticket_statuses WHERE is_closed = 0 ORDER BY id LIMIT 1")->fetchColumn();
    [$prio1, $prio2] = array_map('intval', $c->query("SELECT id FROM ticket_priorities ORDER BY id LIMIT 2")->fetchAll(PDO::FETCH_COLUMN));

    $analyst = function (string $u, int $all) use ($c, $id): int {
        $c->prepare("INSERT INTO analysts (username, password_hash, full_name, email, is_active, is_admin, can_access_all_tenants, can_access_all_modules)
                     VALUES (?, '!', ?, ?, 1, 0, ?, 1)")->execute([$u, $u, "$u@zz.invalid", $all]);
        return $id();
    };
    $ticket = function (string $subj, ?int $tenant) use ($c, $id, $status, $prio1): int {
        $c->prepare("INSERT INTO tickets (ticket_number, subject, status_id, priority_id, tenant_id, created_datetime) VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP())")
          ->execute(['ZZ-' . mt_rand(10000, 99999), $subj, $status, $prio1, $tenant]);
        return $id();
    };
    $wf = function (string $name, string $trigger, ?int $tenant, array $actions) use ($c, $id): int {
        $c->prepare("INSERT INTO workflows (name, trigger_event, conditions, actions, is_active, tenant_id) VALUES (?, ?, '[]', ?, 1, ?)")
          ->execute([$name, $trigger, json_encode($actions), $tenant]);
        return $id();
    };
    $zzall = $analyst('zz-all', 1);
    $zza   = $analyst('zz-a', 0);
    $tA = $ticket('ZZ A', $D); $tB = $ticket('ZZ B', $D); $tD = $ticket('ZZ D', null);
    $log = [['type' => 'log_message', 'args' => ['message' => 'zz']]];
    $w = [
        'shared' => $wf('ZZ shared', 'ticket.created', null, $log),
        'pinA'   => $wf('ZZ A only', 'ticket.created', null, $log),   // given a company in phase 2
        'pinB'   => $wf('ZZ B only', 'ticket.created', null, $log),
    ];
    $fx = ['D' => $D, 'tA' => $tA, 'tB' => $tB, 'tD' => $tD, 'zzall' => $zzall, 'zza' => $zza, 'w' => $w, 'prio2' => $prio2];

    // ---- Phase 1: N=1 ----
    $run = function (string $phase) use ($db, &$fx, &$total) {
        $out = shell_exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg(__FILE__) . " --phase $db $phase " . base64_encode(json_encode($fx)) . ' 2>&1');
        echo preg_replace('/^__RESULT__.*$/m', '', (string)$out);
        if (preg_match('/__RESULT__ (\d+) (\d+)/', (string)$out, $m)) { $total[0] += (int)$m[1]; $total[1] += (int)$m[2]; }
        else { $total[1]++; echo "  FAIL phase $phase did not finish\n"; }
    };
    $run('n1');

    // ---- Phase 2: N=2 (well, three: Default, A, B) ----
    $c->exec("DELETE FROM workflow_executions");
    $c->prepare("INSERT INTO tenants (name, is_default, is_active) VALUES ('ZZ Acme', 0, 1)")->execute(); $A = $id();
    $c->prepare("INSERT INTO tenants (name, is_default, is_active) VALUES ('ZZ Globex', 0, 1)")->execute(); $B = $id();
    $c->prepare("UPDATE tickets SET tenant_id = ? WHERE id = ?")->execute([$A, $tA]);
    $c->prepare("UPDATE tickets SET tenant_id = ? WHERE id = ?")->execute([$B, $tB]);
    $c->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$zza, $A]);
    $zzb = $analyst('zz-b', 0);
    $c->prepare("INSERT INTO analyst_tenant_access (analyst_id, tenant_id) VALUES (?, ?)")->execute([$zzb, $B]);
    $c->prepare("UPDATE workflows SET tenant_id = ? WHERE id = ?")->execute([$A, $w['pinA']]);
    $c->prepare("UPDATE workflows SET tenant_id = ? WHERE id = ?")->execute([$B, $w['pinB']]);
    $c->prepare("INSERT INTO teams (name, display_order) VALUES ('ZZ Team', 1)")->execute(); $team = $id();
    $c->prepare("INSERT INTO departments (name, is_active) VALUES ('ZZ Dept', 1)")->execute(); $dept = $id();
    $c->prepare("INSERT INTO ticket_categories (name, is_active, is_portal_visible, display_order, tenant_id) VALUES ('ZZ Shared cat', 1, 1, 1, NULL)")->execute(); $catShared = $id();
    $c->prepare("INSERT INTO ticket_categories (name, is_active, is_portal_visible, display_order, tenant_id) VALUES ('ZZ B cat', 1, 1, 2, ?)")->execute([$B]); $catB = $id();
    $act = fn(string $type, array $args) => [['type' => $type, 'args' => $args]];
    $tid = '{{ticket.id}}';
    require_once "$ROOT/includes/encryption.php";
    $w += [
        'supShared'   => $wf('ZZ supplier shared', 'supplier.created', null, $log),
        'supA'        => $wf('ZZ supplier A', 'supplier.created', $A, $log),
        'crossA'      => $wf('ZZ A hits B', 'zz.manual', $A, $act('set_ticket_priority', ['ticket_id' => (string)$tB, 'priority_id' => (string)$prio2])),
        'ownA'        => $wf('ZZ A own', 'zz.manual', $A, $act('set_ticket_priority', ['ticket_id' => $tid, 'priority_id' => (string)$prio2])),
        'crossShared' => $wf('ZZ shared hits B', 'zz.manual', null, $act('add_ticket_note', ['ticket_id' => (string)$tB, 'note' => 'zz'])),
        'assignB'     => $wf('ZZ A assigns to B man', 'zz.manual', $A, $act('assign_ticket', ['ticket_id' => $tid, 'analyst_id' => (string)$zzb])),
        'assignA'     => $wf('ZZ A assigns to A man', 'zz.manual', $A, $act('assign_ticket', ['ticket_id' => $tid, 'analyst_id' => (string)$zza])),
        'taskA'       => $wf('ZZ A task', 'zz.manual', $A, $act('create_task', ['title' => 'ZZ task'])),
        'taskShared'  => $wf('ZZ shared task', 'zz.manual', null, $act('create_task', ['title' => 'ZZ task', 'ticket_id' => $tid])),
        'teamA'       => $wf('ZZ A team', 'zz.manual', $A, $act('assign_ticket_team', ['ticket_id' => $tid, 'team_id' => (string)$team])),
        'deptA'       => $wf('ZZ A dept', 'zz.manual', $A, $act('set_ticket_department', ['ticket_id' => $tid, 'department_id' => (string)$dept])),
        'catShared'   => $wf('ZZ cat shared', 'zz.manual', $A, $act('set_ticket_category', ['ticket_id' => $tid, 'category_id' => (string)$catShared])),
        'catB'        => $wf('ZZ cat B', 'zz.manual', null, $act('set_ticket_category', ['ticket_id' => $tid, 'category_id' => (string)$catB])),
        'teamCross'   => $wf('ZZ A team on B', 'zz.manual', $A, $act('assign_ticket_team', ['ticket_id' => (string)$tB, 'team_id' => (string)$team])),
        'hookShared'  => $wf('ZZ hook', 'zz.manual', null, $act('send_webhook', ['preset' => 'custom', 'url' => 'https://example.invalid/zz', 'secret' => 'zz-secret', 'body' => '{}'])),
    ];
    $fx = array_merge($fx, ['A' => $A, 'B' => $B, 'zzb' => $zzb, 'team' => $team, 'dept' => $dept, 'catShared' => $catShared, 'w' => $w]);
    $run('n2');

    // ---- Phase 3: the columns not there yet ----
    foreach (['workflows' => 'fk_workflows_tenant', 'workflow_executions' => 'fk_we_tenant'] as $t => $fk) {
        try { $c->exec("ALTER TABLE `$t` DROP FOREIGN KEY `$fk`"); } catch (Exception $e) {}
        $c->exec("ALTER TABLE `$t` DROP COLUMN `tenant_id`");
    }
    $run('nocol');
} finally {
    $admin->exec("DROP DATABASE IF EXISTS `$db`");
    // The child sessions, so nothing is left behind.
    foreach (glob(rtrim(preg_replace('/^\d+;/', '', session_save_path() ?: sys_get_temp_dir()), '/\\') . '/sess_zzwf209*') ?: [] as $f) @unlink($f);
}
printf("\n%d passed, %d failed\n", $total[0], $total[1]);
exit($total[1] ? 1 : 0);
