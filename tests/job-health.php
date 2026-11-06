<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Background job health (#182) - the recorder and the health rules.
 *
 *   php tests/job-health.php [base-url]
 *
 * Proves the rules agreed on the issue, each against a positive control:
 *   1. freshness reads only SCHEDULED runs - a page load or Run now never keeps a job fresh;
 *   2. a skipped run never clears a failure;
 *   3. a failing source stays failing until THAT source succeeds, or stops being expected;
 *   4. "not used here" clears a job's health;
 * plus stuck runs, crashed runs, cleaned messages, and (with a base URL) the REST
 * address answering 503 under ?strict=1.
 *
 * Uses a made-up job key ('zz_test_job') with its own definition, so nothing it does
 * touches the health of a real job. Deletes every row it wrote.
 *
 * @wiki Background-Jobs-Developer-Guide
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/job_runs.php';
require_once __DIR__ . '/../includes/job_health.php';

$BASE = isset($argv[1]) ? rtrim($argv[1], '/') . '/' : null;
$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

$conn = connectToDatabase();
const ZZ = 'zz_test_job';
$sources = ['src:a', 'src:b'];
$def = ['key' => ZZ, 'name_key' => 'x', 'affects_key' => 'x', 'command' => 'php zz.php', 'web' => true,
        'interval' => 5, 'grace' => 10, 'max_runtime' => 10,
        'needed' => fn() => true, 'sources' => function () use (&$sources) { return $sources; }];
$noSrc = $def; unset($noSrc['sources']);

/** A finished run of the test job, $agoMin minutes ago. */
function zzRun(PDO $c, string $trigger, string $outcome, int $agoMin, array $src = []): int {
    $c->prepare("INSERT INTO job_runs (job_key, trigger_kind, outcome, started_datetime, finished_datetime, summary)
                 VALUES (?, ?, ?, UTC_TIMESTAMP() - INTERVAL ? MINUTE, UTC_TIMESTAMP() - INTERVAL ? MINUTE, ?)")
      ->execute([ZZ, $trigger, $outcome, $agoMin, $agoMin, "zz {$outcome}"]);
    $id = (int)$c->lastInsertId();
    foreach ($src as $k => $okFlag) {
        $c->prepare("INSERT INTO job_run_sources (run_id, job_key, source_key, source_label, ok, message, created_datetime)
                     VALUES (?, ?, ?, ?, ?, ?, UTC_TIMESTAMP() - INTERVAL ? MINUTE)")
          ->execute([$id, ZZ, $k, strtoupper($k), $okFlag ? 1 : 0, $okFlag ? null : 'broken', $agoMin]);
    }
    return $id;
}
function zzClear(PDO $c): void {
    $c->exec("DELETE FROM job_run_sources WHERE job_key = '" . ZZ . "'");
    $c->exec("DELETE FROM job_runs WHERE job_key = '" . ZZ . "'");
}
$h = fn(array $d, array $cfg = []) => jobHealthOne($conn, $d, $cfg);
$codes = fn(array $j) => array_map(fn($p) => $p['code'], $j['problems']);

echo "\nBackground job health (#182)\n" . str_repeat('=', 70) . "\n";
try {
    zzClear($conn);

    echo "\nRule 1: freshness reads only scheduled runs\n";
    ok('no runs at all is never seen', $h($noSrc)['status'] === 'never_seen');
    zzRun($conn, 'page', 'ok', 1);
    $j = $h($noSrc);
    ok('a page-load run alone is still never seen', $j['status'] === 'never_seen');
    ok('...and says page loads have run it', !empty($j['problems'][0]['other_runs']));
    zzRun($conn, 'cron', 'ok', 2);
    ok('POSITIVE CONTROL: a scheduled run 2 minutes ago is healthy', $h($noSrc)['status'] === 'healthy');
    zzClear($conn);
    zzRun($conn, 'web', 'ok', 120);
    zzRun($conn, 'manual', 'ok', 1);
    zzRun($conn, 'page', 'ok', 0);
    $j = $h($noSrc);
    ok('scheduled success 2 hours ago is OVERDUE although Run now and a page ran since', $j['status'] === 'overdue', $j['status']);
    ok('...and the web address counts as scheduled (it is the last scheduled success)', $j['last_scheduled_success'] !== null);
    ok('...a longer configured interval makes it fresh again', $h($noSrc, ['interval' => 180])['status'] === 'healthy');

    echo "\nRule 2: a skip never clears a failure\n";
    zzClear($conn);
    zzRun($conn, 'cron', 'failed', 3);
    zzRun($conn, 'cron', 'skipped', 2);
    zzRun($conn, 'cron', 'skipped', 1);
    $j = $h($noSrc);
    ok('failed then two skips is still FAILING', in_array('failing', $codes($j), true), implode(',', $codes($j)));
    zzRun($conn, 'cron', 'ok', 0);
    ok('POSITIVE CONTROL: a real successful run clears it', $h($noSrc)['status'] === 'healthy');
    zzClear($conn);
    zzRun($conn, 'cron', 'ok', 4);
    zzRun($conn, 'cron', 'partial', 3);
    zzRun($conn, 'cron', 'skipped', 1);
    ok('a partial run with no named sources is partial, and a skip does not clear it', $h($noSrc)['status'] === 'partial');

    echo "\nRule 3: recovery is per source\n";
    zzClear($conn);
    zzRun($conn, 'cron', 'partial', 5, ['src:a' => false, 'src:b' => true]);
    zzRun($conn, 'cron', 'ok', 1, ['src:b' => true]);   // A not due this run
    $j = $h($def);
    ok('A failed, a later run processed only B: still PARTIAL', $j['status'] === 'partial', $j['status']);
    ok('...naming A', ($j['failing_sources'][0]['key'] ?? '') === 'src:a');
    zzRun($conn, 'cron', 'ok', 0, ['src:a' => true]);
    ok('POSITIVE CONTROL: once A itself succeeds, healthy', $h($def)['status'] === 'healthy');
    zzRun($conn, 'cron', 'partial', 0, ['src:a' => false]);
    $sources = ['src:b'];   // A switched off / removed
    ok('a failing source that is no longer expected stops counting', $h($def)['status'] === 'healthy');
    $sources = ['src:a', 'src:b'];
    ok('POSITIVE CONTROL: expected again, it counts again', $h($def)['status'] === 'partial');

    echo "\nRule 4: not used here\n";
    ok('marking it not used here clears it', $h($def, ['not_used' => true])['status'] === 'not_used');
    $nn = $def; $nn['needed'] = fn() => false;
    ok('a job whose feature is not in use is not needed', $h($nn)['status'] === 'not_needed');

    echo "\nStuck and crashed runs\n";
    zzClear($conn);
    zzRun($conn, 'cron', 'ok', 30);
    $conn->prepare("INSERT INTO job_runs (job_key, trigger_kind, outcome, started_datetime) VALUES (?, 'cron', 'running', UTC_TIMESTAMP() - INTERVAL 60 MINUTE)")->execute([ZZ]);
    $stuckId = (int)$conn->lastInsertId();
    ok('a run started an hour ago (longest normal: 10 min) and never finished is STUCK', $h($noSrc)['status'] === 'stuck');
    $conn->prepare("INSERT INTO job_runs (job_key, trigger_kind, outcome, started_datetime) VALUES (?, 'cron', 'running', UTC_TIMESTAMP() - INTERVAL 1 MINUTE)")->execute([ZZ]);
    ok('POSITIVE CONTROL: a run started a minute ago is not stuck', !in_array('stuck', $codes($h($noSrc)), true));

    echo "\nThe recorder\n";
    zzClear($conn);
    $conn->prepare("INSERT INTO job_runs (job_key, trigger_kind, outcome, started_datetime) VALUES ('task_recurrence', 'cron', 'running', UTC_TIMESTAMP() - INTERVAL 90 MINUTE)")->execute();
    $dead = (int)$conn->lastInsertId();
    $id = JobRun::start($conn, 'task_recurrence', 'manual', null);
    JobRun::source($conn, $id, 'task_recurrence', 'series:zz', 'ZZ', false, 'x');
    JobRun::source($conn, $id, 'task_recurrence', 'series:zz2', 'ZZ2', true);
    JobRun::finish($conn, $id);
    $o = $conn->query("SELECT outcome FROM job_runs WHERE id = {$id}")->fetchColumn();
    ok('one failed and one good source makes the run PARTIAL', $o === 'partial', (string)$o);
    $d = $conn->query("SELECT outcome, summary FROM job_runs WHERE id = {$dead}")->fetch(PDO::FETCH_ASSOC);
    ok('a run that died long ago is marked failed when a later one finishes', $d['outcome'] === 'failed' && str_starts_with($d['summary'], 'Never finished'));
    $conn->exec("DELETE FROM job_run_sources WHERE run_id IN ({$id}, {$dead})");
    $conn->exec("DELETE FROM job_runs WHERE id IN ({$id}, {$dead})");

    $c = JobRun::clean('GET https://user:secret@jira.example.com/rest?token=abcd failed; password=hunter2 key 0123456789abcdef0123456789abcdef');
    ok('clean() removes credentials in URLs', !str_contains($c, 'secret'));
    ok('clean() removes query strings', !str_contains($c, 'abcd'));
    ok('clean() removes password=...', !str_contains($c, 'hunter2'));
    ok('clean() removes long hex tokens', !str_contains($c, '0123456789abcdef'));
    ok('POSITIVE CONTROL: clean() keeps the host and the words', str_contains($c, 'jira.example.com') && str_contains($c, 'failed'));

    // ---- REST: ?strict=1 answers 503 while anything needed is unhealthy ----
    if ($BASE !== null) {
        echo "\nREST /jobs/health\n";
        $admin = (int)$conn->query("SELECT id FROM analysts WHERE is_active = 1 AND is_admin = 1 ORDER BY id LIMIT 1")->fetchColumn();
        $raw = 'fitsm_zz' . bin2hex(random_bytes(16));
        $conn->prepare("INSERT INTO api_keys (name, key_hash, key_prefix, analyst_id, permissions, active, created_datetime)
                        VALUES ('ZZ job health test', ?, ?, ?, ?, 1, UTC_TIMESTAMP())")
             ->execute([hash('sha256', $raw), substr($raw, 0, 12), $admin, json_encode(['job_health' => ['read']])]);
        $keyId = (int)$conn->lastInsertId();
        $call = function (string $q) use ($BASE, $raw) {
            $ch = curl_init($BASE . 'api/v1/jobs/health' . $q);
            curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $raw]]);
            $body = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            return [$code, json_decode((string)$body, true)];
        };
        try {
            $healthy = jobHealthSummary($conn)['healthy'];
            [$c1, $b1] = $call('');
            ok('GET /jobs/health answers 200 without strict', $c1 === 200, (string)$c1);
            ok('...with every job', count($b1['data']['jobs'] ?? []) === count(jobRegistry()));
            ok('...and no command lines in it', !str_contains(json_encode($b1), 'cron/') && !str_contains(json_encode($b1), 'cron\/'));
            [$c2] = $call('?strict=1');
            ok('?strict=1 answers ' . ($healthy ? '200 (this install is healthy)' : '503 (this install is unhealthy)'), $c2 === ($healthy ? 200 : 503), (string)$c2);
            $conn->prepare("UPDATE api_keys SET permissions = ? WHERE id = ?")->execute([json_encode(['tickets' => ['read']]), $keyId]);
            [$c3] = $call('');
            ok('a key without job_health.read is refused', $c3 === 403, (string)$c3);
        } finally {
            $conn->prepare("DELETE FROM api_keys WHERE id = ?")->execute([$keyId]);
        }
    }
} finally {
    zzClear($conn);
}

// ── #182 review: a lookup that FAILS is "unknown", never "not needed" ───────
echo "\nFailed lookups are unknown, not fine (Dr4inder's review)\n";

/** Dr4inder's reproduction: every read throws, no real database touched. */
class SyntheticUnavailablePDO extends PDO {
    public function __construct() {}
    public function prepare(string $query, array $options = []): PDOStatement|false { throw new PDOException('Synthetic database read failure'); }
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false { throw new PDOException('Synthetic database read failure'); }
}
$res = jobHealthSummary(new SyntheticUnavailablePDO());
$states = array_count_values(array_column($res['jobs'], 'status'));
ok('every read failing: NOT healthy (it used to say healthy, 13 not needed)', $res['healthy'] === false, json_encode($states));
ok('...every job is "unknown", none "not needed"', ($states['unknown'] ?? 0) === count(jobRegistry()) + 1 /* + the pushed sources (GH #199), unreadable too */ && !isset($states['not_needed']), json_encode($states));

/** The real database, except for queries matching $fail - which throw, as a failed read or (code 42S02) a missing table. */
class SelectiveFailPDO extends PDO {
    public ?string $fail = null;
    public string $code = 'HY000';
    private function boom(string $sql): void {
        if ($this->fail !== null && preg_match($this->fail, $sql)) {
            $e = new PDOException('Synthetic failure');
            $p = new ReflectionProperty(Exception::class, 'code');
            $p->setValue($e, $this->code);
            throw $e;
        }
    }
    public function prepare(string $query, array $options = []): PDOStatement|false { $this->boom($query); return parent::prepare($query, $options); }
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs): PDOStatement|false {
        $this->boom($query);
        return $fetchMode === null ? parent::query($query) : parent::query($query, $fetchMode, ...$fetchModeArgs);
    }
}
$sel = new SelectiveFailPDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USERNAME, DB_PASSWORD, dbConnectionOptions());
$normal = jobHealthAll($sel);

$sel->fail = '/FROM task_recurrences WHERE is_active = 1 AND mode/';
$h = jobHealthAll($sel);
ok('a failed REQUIREMENT lookup: that job is unknown', $h['task_recurrence']['status'] === 'unknown' && $h['task_recurrence']['problems'][0]['what'] === 'requirement', $h['task_recurrence']['status']);
ok('...and the other jobs are judged as before', $h['domains']['status'] === $normal['domains']['status'] && $h['sla_breach']['status'] === $normal['sla_breach']['status']);

$sel->code = '42S02';
$h = jobHealthAll($sel);
ok('control: the same table MISSING (never set up) is an answer - not needed, not unknown', $h['task_recurrence']['status'] === 'not_needed', $h['task_recurrence']['status']);
$sel->code = 'HY000';

$sel->fail = '/FROM job_run_sources/';
$h = jobHealthAll($sel);
$unk = array_filter($h, fn($j) => $j['status'] === 'unknown' && ($j['problems'][0]['what'] ?? '') === 'source health');
ok('a failed SOURCE HEALTH read: unknown, not "no failing sources"', count($unk) === count(jobRegistry()), count($unk) . ' of ' . count(jobRegistry()));

$sel->fail = '/SELECT id FROM integration_connections WHERE is_active = 1$/';
$h = jobHealthAll($sel);
if ($normal['integration_poll']['needed']) {
    ok('a failed read of WHICH SOURCES still exist: unknown, not "source removed"', $h['integration_poll']['status'] === 'unknown', $h['integration_poll']['status']);
}

$sel->fail = "/setting_key = \\?/";
$h = jobHealthAll($sel);
ok('a failed read of the job SETTINGS: every job unknown', count(array_filter($h, fn($j) => $j['status'] === 'unknown')) === count(jobRegistry()));

$sel->fail = '/FROM job_runs/';
$s = jobHealthSummary($sel);
ok('a failed read of the RUN HISTORY: unhealthy, so the strict address answers 503', $s['healthy'] === false);

$sel->fail = null;
ok('control: nothing failing, nothing unknown', !array_filter(jobHealthAll($sel), fn($j) => $j['status'] === 'unknown'));

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
