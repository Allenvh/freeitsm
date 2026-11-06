<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables and only tidies up if it runs
   to the end. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * GH #199 Part A: the virtual estate ingest, with synthetic Hyper-V reports only.
 *
 * Every scenario the issue's criterion 7 names, as a sequence of reports: first sight,
 * replay, conflict, live migration (placement history), VM rename, host rename (same
 * UUID), an unreadable host (kept, not confirmed - never "gone"), a stale report, a
 * removed VM (not seen, never deleted), a copy-imported VM (new id: raised for review,
 * same machine keeps the old record), a host with no usable UUID (name-based), a report
 * for part of the estate (only its scope is judged), a section that fails alone (207,
 * resend retries it), the company, the retention setting, REST key gating, and job health.
 *
 * Everything lives on a throwaway connection named ZZVE-..., deleted (with its estate,
 * by cascade) in `finally`.
 *
 * Run:  php tests/virtual-estate.php   (FREEITSM_BASE_URL, default http://localhost/freeitsm-app)
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/services/virtual_estate.php';
require_once __DIR__ . '/../includes/job_registry.php';
require_once __DIR__ . '/../includes/job_health.php';
require_once __DIR__ . '/fixtures/virtual-estate/synthetic.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nVirtual estate ingest (GH #199 Part A)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$base = getenv('FREEITSM_BASE_URL') ?: 'http://localhost/freeitsm-app';
$default = getDefaultTenantId($conn);
$otherTenant = (int)$conn->query("SELECT id FROM tenants WHERE id <> $default ORDER BY id LIMIT 1")->fetchColumn();
$connIds = []; $keyIds = [];
$t0 = time() - 4 * 3600;   // the scenario spans ~2.5 hours of observed time, all in the past
$at = fn(int $min) => gmdate('Y-m-d\TH:i:s\Z', $t0 + $min * 60);
$dbAt = fn(int $min) => gmdate('Y-m-d H:i:s', $t0 + $min * 60);
$q = fn(string $sql) => $conn->query($sql)->fetchColumn();

function rest(string $base, string $key, string $method, string $path, ?array $body = null): array {
    $ch = curl_init($base . '/api/v1/index.php?path=' . rawurlencode($path));
    $opts = [CURLOPT_RETURNTRANSFER => true, CURLOPT_CUSTOMREQUEST => $method, CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $key]];
    if ($body !== null) { $opts[CURLOPT_POSTFIELDS] = json_encode($body); }
    curl_setopt_array($ch, $opts);
    $res = json_decode((string)curl_exec($ch), true) ?? [];
    return [(int)curl_getinfo($ch, CURLINFO_HTTP_CODE), $res];
}

try {
    if (!VirtualEstateService::ready($conn)) { throw new RuntimeException('Run Database Verification first: virt_connections is missing.'); }
    $mkConn = function (string $name, ?int $tenant) use ($conn, &$connIds): int {
        $conn->prepare("INSERT INTO virt_connections (name, source_kind, tenant_id, expected_interval_minutes, grace_minutes, created_datetime)
            VALUES (?, 'hyperv', ?, 15, 15, UTC_TIMESTAMP())")->execute([$name, $tenant]);
        return $connIds[] = (int)$conn->lastInsertId();
    };
    $cid = $mkConn('ZZVE-' . bin2hex(random_bytes(3)), $otherTenant ?: null);
    $vm = fn(string $name) => $conn->query("SELECT * FROM virt_vms WHERE connection_id = $cid AND name = " . $conn->quote($name) . " ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    $host = fn(string $name) => $conn->query("SELECT * FROM virt_hosts WHERE connection_id = $cid AND name = " . $conn->quote($name) . " ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);

    // A small estate by hand: one two-node cluster and one standalone host.
    $e = ['clusters' => ['CL-ZZ' => veUuid('cluster:CL-ZZ')],
          'hosts' => ['HV-A' => [veUuid('host:HV-A'), 'CL-ZZ'], 'HV-B' => [veUuid('host:HV-B'), 'CL-ZZ'], 'HV-S' => [veUuid('host:HV-S'), null]],
          'vms' => ['VM1' => [veUuid('vm:VM1'), 'HV-A', true], 'VM2' => [veUuid('vm:VM2'), 'HV-A', false], 'VM3' => [veUuid('vm:VM3'), 'HV-S', false]]];

    // ---- First sight -----------------------------------------------------------------------
    $r = VirtualEstateService::submit($conn, $cid, veReport($e, $at(0)));
    ok('a first report applies', $r['outcome'] === 'applied' && $r['status'] === 200, json_encode($r));
    ok('...a cluster, three hosts, three VMs, one role', (int)$q("SELECT COUNT(*) FROM virt_clusters WHERE connection_id = $cid") === 1
        && (int)$q("SELECT COUNT(*) FROM virt_hosts WHERE connection_id = $cid") === 3 && (int)$q("SELECT COUNT(*) FROM virt_vms WHERE connection_id = $cid") === 3
        && (int)$q("SELECT COUNT(*) FROM virt_cluster_roles WHERE connection_id = $cid") === 1);
    ok('...hosts identified by hardware UUID, the nodes in their cluster', $host('HV-A')['external_id'] === veUuid('host:HV-A')
        && $host('HV-A')['identity_kind'] === 'source' && $host('HV-A')['cluster_id'] !== null && $host('HV-S')['cluster_id'] === null);
    ok('...a VM knows its host, its cluster, and whether it is highly available', (int)$vm('VM1')['host_id'] === (int)$host('HV-A')['id']
        && (int)$vm('VM1')['is_clustered'] === 1 && (int)$vm('VM2')['is_clustered'] === 0 && count(json_decode($vm('VM1')['possible_owners_json'], true)) === 2);
    ok('...and disks and adapters', (int)$q("SELECT COUNT(*) FROM virt_vm_disks d JOIN virt_vms v ON v.id = d.vm_id WHERE v.connection_id = $cid") === 3);
    ok('every object carries the connection\'s company', (int)$q("SELECT COUNT(*) FROM virt_vms WHERE connection_id = $cid AND " . ($otherTenant ? "tenant_id = $otherTenant" : 'tenant_id IS NULL')) === 3);
    ok('one open placement per VM', (int)$q("SELECT COUNT(*) FROM virt_placements p JOIN virt_vms v ON v.id = p.vm_id WHERE v.connection_id = $cid AND p.to_observed_at IS NULL") === 3);

    // ---- Replay and conflict ----------------------------------------------------------------
    $r = VirtualEstateService::submit($conn, $cid, veReport($e, $at(0)));
    ok('🔴 the same report again is a replay - safe to resend for ever', $r['outcome'] === 'replayed');
    $changed = $e; $changed['vms']['VM2'][1] = 'HV-B';
    $code = null;
    try { VirtualEstateService::submit($conn, $cid, veReport($changed, $at(0))); } catch (ServiceError $x) { $code = $x->errorCode; }
    ok('🔴 a different report at the same observed time is a conflict', $code === 'observation_conflict', (string)$code);
    ok('...and changed nothing', (int)$vm('VM2')['host_id'] === (int)$host('HV-A')['id']);

    // ---- Live migration -------------------------------------------------------------------
    $e['vms']['VM1'][1] = 'HV-B';
    VirtualEstateService::submit($conn, $cid, veReport($e, $at(10)));
    $v1 = $vm('VM1');
    ok('a live migration moves the same VM record to the new host', (int)$v1['host_id'] === (int)$host('HV-B')['id']
        && (int)$q("SELECT COUNT(*) FROM virt_vms WHERE connection_id = $cid AND name = 'VM1'") === 1);
    ok('...and the placement history has both hosts, the first one closed', (int)$q("SELECT COUNT(*) FROM virt_placements WHERE vm_id = {$v1['id']}") === 2
        && $q("SELECT to_observed_at FROM virt_placements WHERE vm_id = {$v1['id']} AND host_id = {$host('HV-A')['id']}") === $dbAt(10));

    // ---- Renames ----------------------------------------------------------------------------
    $e['vms']['VM2-RENAMED'] = $e['vms']['VM2']; unset($e['vms']['VM2']);
    $e['hosts']['HV-S2'] = $e['hosts']['HV-S']; unset($e['hosts']['HV-S']);
    $e['vms']['VM3'][1] = 'HV-S2';
    $sBefore = (int)$host('HV-S')['id'];
    VirtualEstateService::submit($conn, $cid, veReport($e, $at(20)));
    ok('🔴 a renamed VM keeps its record (same Get-VM Id)', $vm('VM2-RENAMED') && $vm('VM2-RENAMED')['external_id'] === veUuid('vm:VM2') && !$vm('VM2'));
    ok('🔴 a renamed host keeps its record (same hardware UUID)', (int)$host('HV-S2')['id'] === $sBefore && !$host('HV-S'));
    ok('...and its VMs stay on it', (int)$vm('VM3')['host_id'] === $sBefore);
    ok('...with nothing raised for review', (int)$q("SELECT COUNT(*) FROM virt_identity_reviews WHERE connection_id = $cid") === 0);

    // ---- An unreadable host -----------------------------------------------------------------
    $r = VirtualEstateService::submit($conn, $cid, veReport($e, $at(30), ['unreadable' => [['host', 'HV-B']]]));
    $v1 = $vm('VM1');
    ok('🔴 a VM on an unreadable host is kept, marked not confirmed - never "gone"', $v1['not_seen_since'] === null && $v1['not_confirmed_since'] === $dbAt(30), json_encode([$v1['not_seen_since'], $v1['not_confirmed_since']]));
    ok('...the host too', $host('HV-B')['not_seen_since'] === null && $host('HV-B')['not_confirmed_since'] !== null);
    ok('...and the run is partial on job health', $q("SELECT outcome FROM job_runs WHERE job_key = 'virt_push_$cid' ORDER BY id DESC LIMIT 1") === 'partial');
    VirtualEstateService::submit($conn, $cid, veReport($e, $at(40)));
    ok('read again, it is confirmed again', $vm('VM1')['not_confirmed_since'] === null);

    // ---- Stale ------------------------------------------------------------------------------
    $old = $e; $old['vms']['VM1'][1] = 'HV-A';
    $r = VirtualEstateService::submit($conn, $cid, veReport($old, $at(35)));
    ok('🔴 an older report is stale and changes nothing', $r['outcome'] === 'stale' && (int)$vm('VM1')['host_id'] === (int)$host('HV-B')['id']);

    // ---- Removal is not deletion ------------------------------------------------------------
    $gone = $e; unset($gone['vms']['VM2-RENAMED']);
    VirtualEstateService::submit($conn, $cid, veReport($gone, $at(50)));
    ok('🔴 a VM missing from a complete report is not seen since then - not deleted', $vm('VM2-RENAMED') && $vm('VM2-RENAMED')['not_seen_since'] === $dbAt(50));
    ok('...its disks are kept with it', (int)$q("SELECT COUNT(*) FROM virt_vm_disks WHERE vm_id = " . (int)$vm('VM2-RENAMED')['id']) === 1);

    // ---- Part of the estate -------------------------------------------------------------------
    $only = $gone; unset($only['vms']['VM3']);
    $r = VirtualEstateService::submit($conn, $cid, veReport($only, $at(55), ['scope' => [['cluster', 'CL-ZZ']]]));
    ok('a report for one cluster applies to that cluster only', $r['outcome'] === 'applied' && $vm('VM3')['not_seen_since'] === null, json_encode($r['sections']['vms'] ?? $r));

    // ---- A copy-imported VM (new id) ------------------------------------------------------------
    $copy = $gone; $copy['vms']['VM3'] = [veUuid('vm:VM3-copy'), 'HV-S2', false];
    $oldVm3 = $vm('VM3');
    $r = VirtualEstateService::submit($conn, $cid, veReport($copy, $at(60)));
    $case = $conn->query("SELECT * FROM virt_identity_reviews WHERE connection_id = $cid AND object_kind = 'vm' AND status = 'open'")->fetch(PDO::FETCH_ASSOC);
    ok('🔴 a VM back with a new id while the old one went is raised for review', $r['review_raised'] === 1 && $case && (int)$case['old_object_id'] === (int)$oldVm3['id'], json_encode($r));
    ok('...and never merged automatically: both records exist', (int)$q("SELECT COUNT(*) FROM virt_vms WHERE connection_id = $cid AND name = 'VM3'") === 2);
    $again = VirtualEstateService::submit($conn, $cid, veReport($copy, $at(65)));
    ok('...raised once, not on every report', $again['review_raised'] === 0);
    $ctx = new ActorContext(1, null, 'ui', 'en', 'Administrator');
    $res = VirtualEstateService::decideReview($conn, $ctx, (int)$case['id'], 'same');
    $kept = $conn->query("SELECT * FROM virt_vms WHERE id = " . (int)$oldVm3['id'])->fetch(PDO::FETCH_ASSOC);
    ok('"Same machine" keeps the OLD record (and its id), under the new identity', $res['kept_id'] === (int)$oldVm3['id']
        && $kept['external_id'] === veUuid('vm:VM3-copy') && $kept['not_seen_since'] === null && $kept['first_seen_at'] === $oldVm3['first_seen_at']);
    ok('...the new record is gone and its disks moved over', (int)$q("SELECT COUNT(*) FROM virt_vms WHERE connection_id = $cid AND name = 'VM3'") === 1
        && (int)$q("SELECT COUNT(*) FROM virt_vm_disks WHERE vm_id = " . (int)$oldVm3['id']) === 1);
    ok('...the placement history continues', (int)$q("SELECT COUNT(*) FROM virt_placements WHERE vm_id = " . (int)$oldVm3['id']) >= 1);
    $code = null;
    try { VirtualEstateService::decideReview($conn, $ctx, (int)$case['id'], 'different'); } catch (ServiceError $x) { $code = $x->errorCode; }
    ok('a decided case cannot be decided again', $code === 'already_decided', (string)$code);
    VirtualEstateService::submit($conn, $cid, veReport($copy, $at(70)));
    ok('...and the next report updates the kept record', (int)$q("SELECT COUNT(*) FROM virt_vms WHERE connection_id = $cid AND name = 'VM3'") === 1 && $vm('VM3')['last_seen_observed_at'] === $dbAt(70));

    // A case whose old VM is seen again before anyone decides.
    $back = $copy; $back['vms']['VM9'] = [veUuid('vm:VM9'), 'HV-S2', false];
    VirtualEstateService::submit($conn, $cid, veReport($back, $at(80)));
    $swap = $back; $swap['vms']['VM9'] = [veUuid('vm:VM9-new'), 'HV-S2', false];
    VirtualEstateService::submit($conn, $cid, veReport($swap, $at(90)));
    $case2 = (int)$q("SELECT id FROM virt_identity_reviews WHERE connection_id = $cid AND name = 'VM9' AND status = 'open'");
    VirtualEstateService::submit($conn, $cid, veReport(['vms' => $swap['vms'] + ['VM9-OLD' => [veUuid('vm:VM9'), 'HV-S2', false]]] + $swap, $at(95)));
    $code = null;
    try { VirtualEstateService::decideReview($conn, $ctx, $case2, 'same'); } catch (ServiceError $x) { $code = $x->errorCode; }
    ok('🔴 a decision on something that has changed since is refused (review_changed)', $case2 > 0 && $code === 'review_changed', "case $case2 code $code");

    // ---- A host with no usable hardware UUID ------------------------------------------------------
    $zero = $swap; $zero['hosts']['HV-ZERO'] = ['00000000-0000-0000-0000-000000000000', null];
    VirtualEstateService::submit($conn, $cid, veReport($zero, $at(100)));
    ok('🔴 an all-zero UUID is not an identity: the host is name-based, and says so', $host('HV-ZERO')['identity_kind'] === 'name' && $host('HV-ZERO')['external_id'] === 'name:hv-zero');

    // ---- A section that fails alone ----------------------------------------------------------------
    $bad = veReport($zero, $at(110));
    $bad['sections']['vm_networks']['items'][] = ['vm_id' => 'not-a-vm-of-this-connection', 'id' => 'nic-9'];
    $r = VirtualEstateService::submit($conn, $cid, $bad);
    ok('🔴 a bad section is rolled back alone: 207, named, the others applied', $r['status'] === 207 && $r['sections']['vm_networks']['outcome'] === 'failed'
        && $r['sections']['vms']['outcome'] === 'applied', json_encode(array_map(fn($s) => $s['outcome'], $r['sections'])));
    $r = VirtualEstateService::submit($conn, $cid, $bad);
    ok('...sending the same report again retries it (a partial one is not a replay)', $r['outcome'] === 'partial' && $r['status'] === 207);

    // ---- Validation ------------------------------------------------------------------------------
    $code = null;
    try { VirtualEstateService::submit($conn, $cid, ['schema_version' => 1, 'observed_at' => '2026-10-26 09:00:00', 'scope' => [['kind' => 'host', 'name' => 'x']], 'sections' => []]); }
    catch (ServiceError $x) { $code = $x->errorCode; }
    ok('an observed_at without a timezone is refused', $code === 'invalid_field');
    $code = null;
    try { VirtualEstateService::submit($conn, $cid, ['schema_version' => 1, 'observed_at' => $at(120), 'sections' => []]); } catch (ServiceError $x) { $code = $x->errorCode; }
    ok('a report without a scope is refused', $code === 'missing_field');

    // ---- Retention (off by default) ------------------------------------------------------------------
    ok('with retention off, a VM not seen for a long time is still there', (bool)$vm('VM2-RENAMED'));
    $conn->exec("UPDATE virt_vms SET not_seen_since = '2020-01-01 00:00:00' WHERE id = " . (int)$vm('VM2-RENAMED')['id']);
    $conn->exec("UPDATE virt_connections SET retention_days = 30 WHERE id = $cid");
    VirtualEstateService::submit($conn, $cid, veReport($zero, $at(130)));
    ok('with retention set, it is removed once older than that', !$vm('VM2-RENAMED'));

    // ---- Job health ----------------------------------------------------------------------------------
    $all = jobHealthAll($conn);
    $job = $all['virt_push_' . $cid] ?? null;
    ok('the connection is on Background jobs, with its own interval', $job !== null && $job['pushed'] === true && $job['interval'] === 15, json_encode($job['status'] ?? null));
    $conn->exec("UPDATE job_runs SET started_datetime = started_datetime - INTERVAL 2 HOUR, finished_datetime = finished_datetime - INTERVAL 2 HOUR WHERE job_key = 'virt_push_$cid'");
    $job = jobHealthAll($conn)['virt_push_' . $cid];
    ok('🔴 a collector that stopped reporting is overdue there', $job['status'] === 'overdue', $job['status']);
    $c = VirtualEstateService::connection($conn, $cid);
    ok('the connection\'s own freshness agrees', VirtualEstateService::freshness($c, time() + 3 * 3600) === 'overdue' && VirtualEstateService::freshness($c) === 'on_time');

    // ---- REST: only the connection's own key ----------------------------------------------------------
    $mkKey = function (array $perms, ?array $companies) use ($conn, &$keyIds): array {
        $key = 'fitsm_' . bin2hex(random_bytes(24));
        $conn->prepare('INSERT INTO api_keys (name, key_prefix, key_hash, analyst_id, permissions, company_ids) VALUES (?, ?, ?, 1, ?, ?)')
            ->execute(['ZZVE key', substr($key, 0, 12), hash('sha256', $key), json_encode($perms), $companies ? json_encode($companies) : null]);
        $keyIds[] = (int)$conn->lastInsertId();
        return [$key, (int)$conn->lastInsertId()];
    };
    [$own, $ownId] = $mkKey(['virtual_estate' => ['create', 'read']], null);
    [$other] = $mkKey(['virtual_estate' => ['create', 'read']], null);
    $conn->exec("UPDATE virt_connections SET api_key_id = $ownId WHERE id = $cid");
    [$code, $res] = rest($base, $other, 'POST', "/virtual-estate/$cid/reports", veReport($zero, $at(140)));
    ok('🔴 REST: another key, even with the permission, cannot post this connection\'s reports', $code === 403, $code . ' ' . json_encode($res));
    [$code, $res] = rest($base, $own, 'POST', "/virtual-estate/$cid/reports", veReport($zero, $at(140)));
    ok('REST: the connection\'s own key can', $code === 200 && ($res['data']['outcome'] ?? '') === 'applied', $code . ' ' . json_encode($res));
    [$code, $res] = rest($base, $own, 'GET', "/virtual-estate/$cid");
    ok('REST: GET shows it on time, with the counts', $code === 200 && ($res['data']['freshness'] ?? '') === 'on_time' && ($res['data']['counts']['vms'] ?? 0) >= 3, $code . ' ' . json_encode($res['data'] ?? $res));
    if ($otherTenant) {
        [$scoped] = $mkKey(['virtual_estate' => ['read']], [$default]);
        [$code] = rest($base, $scoped, 'GET', "/virtual-estate/$cid");
        ok('🔴 REST: a key for another company cannot even see the connection', $code === 404, (string)$code);
    }
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
} finally {
    foreach ($connIds as $id) {
        try { $conn->prepare("DELETE FROM job_run_sources WHERE job_key = ?")->execute(['virt_push_' . $id]); } catch (Throwable $e) {}
        try { $conn->prepare("DELETE FROM job_runs WHERE job_key = ?")->execute(['virt_push_' . $id]); } catch (Throwable $e) {}
        try { $conn->prepare("DELETE FROM virt_connections WHERE id = ? AND name LIKE 'ZZVE-%'")->execute([$id]); } catch (Throwable $e) {}
    }
    foreach ($keyIds as $k) {
        try { $conn->prepare('DELETE FROM api_key_rate_limits WHERE api_key_id = ?')->execute([$k]); } catch (Throwable $e) {}
        try { $conn->prepare('DELETE FROM api_keys WHERE id = ?')->execute([$k]); } catch (Throwable $e) {}
    }
    echo '  cleanup: ' . (int)$conn->query("SELECT COUNT(*) FROM virt_connections WHERE name LIKE 'ZZVE-%'")->fetchColumn() . ' ZZVE connections, '
        . (int)$conn->query("SELECT COUNT(*) FROM virt_vms")->fetchColumn() . " VMs left in the estate tables\n";
}

echo str_repeat('-', 70) . "\n  {$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
