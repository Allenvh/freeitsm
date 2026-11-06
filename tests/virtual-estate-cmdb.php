<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables and only tidies up if it runs
   to the end. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * GH #199 Part B: source-bound CIs - a Hyper-V estate kept in step with the CMDB.
 *
 * Synthetic reports on a throwaway connection: the preview (create, possible match, the
 * roles that are not VMs), binding a match, switching the sync on, the relationships it owns
 * (member of, runs on - a separate type for highly available VMs - and role in), a live
 * migration moving ONE edge and the same CI, renames, a person's own relationship and data
 * left alone, impact grades fed by discovery (a recorded state, an unreadable node, possible
 * owners), a person deleting a CI, an identity review, retention, the From discovery card
 * (restricted without Assets), and unbinding.
 *
 * Everything is removed in `finally`: the connection (its estate by cascade), every CI the
 * test made, and any class or relationship type the suggestion created that did not exist.
 *
 * Run:  php tests/virtual-estate-cmdb.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/services/virtual_estate.php';
require_once __DIR__ . '/../includes/services/virtual_estate_cmdb.php';
require_once __DIR__ . '/../includes/cmdb_impact.php';
require_once __DIR__ . '/fixtures/virtual-estate/synthetic.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
echo "\nSource-bound CIs (GH #199 Part B)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ctx = new ActorContext(1, null, 'ui', 'en', 'Administrator');
$q = fn(string $sql) => $conn->query($sql)->fetchColumn();
$maxClass = (int)$q('SELECT COALESCE(MAX(id), 0) FROM cmdb_classes');
$maxType = (int)$q('SELECT COALESCE(MAX(id), 0) FROM cmdb_relationship_types');
$cid = 0; $manualCis = [];
$t0 = time() - 6 * 3600;
$at = fn(int $min) => gmdate('Y-m-d\TH:i:s\Z', $t0 + $min * 60);

try {
    $conn->prepare("INSERT INTO virt_connections (name, source_kind, created_datetime) VALUES (?, 'hyperv', UTC_TIMESTAMP())")->execute(['ZZVB-' . bin2hex(random_bytes(3))]);
    $cid = (int)$conn->lastInsertId();
    $e = ['clusters' => ['CL-ZZ' => veUuid('cluster:CL-ZZB')],
          'hosts' => ['HV-A' => [veUuid('host:B-HV-A'), 'CL-ZZ'], 'HV-B' => [veUuid('host:B-HV-B'), 'CL-ZZ'], 'HV-S' => [veUuid('host:B-HV-S'), null]],
          'vms' => ['ZZVB-VM1' => [veUuid('vm:B-VM1'), 'HV-A', true], 'ZZVB-VM2' => [veUuid('vm:B-VM2'), 'HV-A', false], 'ZZVB-VM3' => [veUuid('vm:B-VM3'), 'HV-S', false]]];
    $report = function (array $e, int $min, array $opts = []) {
        $r = veReport($e, gmdate('Y-m-d\TH:i:s\Z', $GLOBALS['t0'] + $min * 60), $opts);
        // A file server role beside the VM roles - the only kind of role that becomes a CI of its own.
        if (isset($r['sections']['cluster_roles'])) {
            $r['sections']['cluster_roles']['items'][] = ['id' => veUuid('role:FS'), 'name' => 'ZZVB-FS', 'cluster_id' => $e['clusters']['CL-ZZ'], 'role_type' => 'FileServer', 'owner_host' => 'HV-A', 'state' => 'Online'];
            $r['sections']['cluster_roles']['items'][] = ['id' => veUuid('role:core'), 'name' => 'Cluster Group', 'cluster_id' => $e['clusters']['CL-ZZ'], 'role_type' => 'Cluster', 'owner_host' => 'HV-B', 'state' => 'Online'];
        }
        return $r;
    };
    VirtualEstateService::submit($conn, $cid, $report($e, 0));
    $vmRow = fn(string $n) => $conn->query("SELECT * FROM virt_vms WHERE connection_id = $cid AND name = " . $conn->quote($n))->fetch(PDO::FETCH_ASSOC);
    $hostRow = fn(string $n) => $conn->query("SELECT * FROM virt_hosts WHERE connection_id = $cid AND name = " . $conn->quote($n))->fetch(PDO::FETCH_ASSOC);
    $ciOf = fn(string $kind, int $virtId) => (int)$q("SELECT cmdb_object_id FROM cmdb_object_sources WHERE object_kind = '$kind' AND virt_object_id = $virtId AND ended_at IS NULL");

    // ---- Mapping and preview ---------------------------------------------------------------
    $s = VirtualEstateCmdbService::suggest($conn);
    ok('the suggestion fills every class and relationship type', count(array_filter($s)) === 8, json_encode($s));
    ok('...its "runs on (highly available)" is placement with failover, "is a member of" redundant membership',
        $q("SELECT redundancy FROM cmdb_relationship_types WHERE id = {$s['runs_on_ha_type_id']}") === 'placement'
        && $q("SELECT redundancy FROM cmdb_relationship_types WHERE id = {$s['member_type_id']}") === 'membership');
    $m = VirtualEstateCmdbService::saveMapping($conn, $ctx, $cid, $s + ['enabled' => 0]);
    // A CI a person made already, with the same name as VM3: a possible match, never bound by name.
    $manual = CmdbService::saveObject($conn, $ctx, ['name' => 'zzvb-vm3', 'class_id' => $s['vm_class_id']])['id'];
    $manualCis[] = $manual;
    $p = VirtualEstateCmdbService::preview($conn, $cid);
    ok('preview: one cluster, three hosts to create', $p['kinds']['cluster']['counts']['create'] === 1 && $p['kinds']['host']['counts']['create'] === 3);
    ok('...one role - the file server, not the VM roles or the cluster\'s own group', $p['kinds']['role']['counts']['create'] === 1
        && $p['kinds']['role']['items']['create'][0]['name'] === 'ZZVB-FS', json_encode($p['kinds']['role']['counts']));
    ok('🔴 ...and VM3 is a possible match to confirm, not bound by its name', $p['kinds']['vm']['counts']['match'] === 1 && $p['kinds']['vm']['counts']['create'] === 2
        && ($p['kinds']['vm']['items']['match'][0]['matches'][0]['id'] ?? 0) === $manual, json_encode($p['kinds']['vm']['counts']));
    ok('nothing was written by the preview', (int)$q("SELECT COUNT(*) FROM cmdb_object_sources WHERE connection_id = $cid") === 0);
    ok('🔴 with the mapping off, a report creates nothing', VirtualEstateCmdbService::sync($conn, $cid) === null);

    VirtualEstateCmdbService::decide($conn, $ctx, $cid, 'vm', (int)$vmRow('ZZVB-VM3')['id'], 'bind', $manual);
    ok('"Bind to this CI" binds the person\'s own CI', $ciOf('vm', (int)$vmRow('ZZVB-VM3')['id']) === $manual);

    // ---- Switched on ------------------------------------------------------------------------
    VirtualEstateCmdbService::saveMapping($conn, $ctx, $cid, $s + ['enabled' => 1]);
    $sum = VirtualEstateCmdbService::sync($conn, $cid);
    ok('the sync creates the rest: cluster, three hosts, the role, two VMs', $sum['created'] === 7, json_encode($sum));
    $vm1Ci = $ciOf('vm', (int)$vmRow('ZZVB-VM1')['id']);
    $hvA = $ciOf('host', (int)$hostRow('HV-A')['id']); $hvB = $ciOf('host', (int)$hostRow('HV-B')['id']);
    $clCi = (int)$q("SELECT cmdb_object_id FROM cmdb_object_sources s JOIN virt_clusters c ON c.id = s.virt_object_id WHERE s.object_kind = 'cluster' AND c.connection_id = $cid AND s.ended_at IS NULL");
    $rel = fn(int $from, int $to, int $type) => (int)$q("SELECT COUNT(*) FROM cmdb_object_relationships WHERE from_object_id = $from AND to_object_id = $to AND relationship_type_id = $type");
    ok('each host is a member of its cluster', $rel($hvA, $clCi, $s['member_type_id']) === 1 && $rel($hvB, $clCi, $s['member_type_id']) === 1);
    ok('🔴 the highly available VM "runs on (highly available)"; the plain one plain "runs on"', $rel($vm1Ci, $hvA, $s['runs_on_ha_type_id']) === 1
        && $rel($ciOf('vm', (int)$vmRow('ZZVB-VM2')['id']), $hvA, $s['runs_on_type_id']) === 1);
    ok('relationships, never containment', (int)$q("SELECT COUNT(*) FROM cmdb_objects o JOIN cmdb_object_sources s ON s.cmdb_object_id = o.id WHERE s.connection_id = $cid AND o.parent_id IS NOT NULL") === 0);
    ok('a second sync changes nothing', ($x = VirtualEstateCmdbService::sync($conn, $cid))['created'] === 0 && $x['edges_added'] === 0 && $x['edges_removed'] === 0);

    // A person's own relationship and data on a source-bound CI.
    $app = CmdbService::saveObject($conn, $ctx, ['name' => 'ZZVB Payroll', 'class_id' => $s['vm_class_id']])['id'];
    $manualCis[] = $app;
    $dep = (int)$q("SELECT id FROM cmdb_relationship_types WHERE LOWER(verb) = 'runs on' LIMIT 1");
    CmdbService::createRelationship($conn, $ctx, $app, ['to_object_id' => $vm1Ci, 'relationship_type_id' => $dep]);

    // ---- Live migration ------------------------------------------------------------------------
    $e['vms']['ZZVB-VM1'][1] = 'HV-B';
    $r = VirtualEstateService::submit($conn, $cid, $report($e, 30));
    ok('a report syncs the CMDB by itself once it is on', isset($r['cmdb']['edges_added']), json_encode($r['cmdb'] ?? null));
    ok('🔴 a live migration moves ONE edge and keeps the same CI', $ciOf('vm', (int)$vmRow('ZZVB-VM1')['id']) === $vm1Ci
        && $rel($vm1Ci, $hvA, $s['runs_on_ha_type_id']) === 0 && $rel($vm1Ci, $hvB, $s['runs_on_ha_type_id']) === 1, json_encode($r['cmdb']));
    ok('🔴 the person\'s own relationship is untouched', $rel($app, $vm1Ci, $dep) === 1);

    // ---- Renames ----------------------------------------------------------------------------------
    $e['vms']['ZZVB-VM2-NEW'] = $e['vms']['ZZVB-VM2']; unset($e['vms']['ZZVB-VM2']);
    VirtualEstateService::submit($conn, $cid, $report($e, 40));
    $vm2Ci = $ciOf('vm', (int)$vmRow('ZZVB-VM2-NEW')['id']);
    ok('a renamed VM renames its CI (names follow the source)', $q("SELECT name FROM cmdb_objects WHERE id = $vm2Ci") === 'ZZVB-VM2-NEW');
    VirtualEstateCmdbService::saveMapping($conn, $ctx, $cid, $s + ['enabled' => 1, 'sync_names' => 0]);
    $e['vms']['ZZVB-VM2-X'] = $e['vms']['ZZVB-VM2-NEW']; unset($e['vms']['ZZVB-VM2-NEW']);
    VirtualEstateService::submit($conn, $cid, $report($e, 50));
    ok('...unless names are set not to follow', $q("SELECT name FROM cmdb_objects WHERE id = $vm2Ci") === 'ZZVB-VM2-NEW');
    VirtualEstateCmdbService::saveMapping($conn, $ctx, $cid, $s + ['enabled' => 1, 'sync_names' => 1]);

    // ---- Impact, fed by discovery -------------------------------------------------------------------
    $grade = function (int $root, int $ci) use ($conn) {
        $b = cmdbBlastRadius($conn, $root, cmdbImpactTenantScope($conn, $root));
        foreach ($b['nodes'] as $n) { if ($n['id'] === $ci) { return [$n['grade'], $b]; } }
        return [null, $b];
    };
    [$g, $b] = $grade($hvB, $vm1Ci);
    $assumed = array_filter($b['limits'], fn($l) => is_array($l) && $l['code'] === 'assumed_available');
    ok('🔴 HV-B fails: the HA VM may be interrupted - HV-A is UP by discovery, not assumed', $g === 'may_be_interrupted' && !$assumed, json_encode([$g, $b['limits']]));
    VirtualEstateService::submit($conn, $cid, $report($e, 60, ['unreadable' => [['host', 'HV-A']]]));
    [$g] = $grade($hvB, $vm1Ci);
    ok('🔴 HV-A unreadable in the last report: the HA VM is unknown, not "may be interrupted"', $g === 'unknown', (string)$g);
    VirtualEstateService::submit($conn, $cid, $report($e, 70));
    $conn->exec("UPDATE virt_vms SET possible_owners_json = '[" . (int)$hostRow('HV-B')['id'] . "]' WHERE id = " . (int)$vmRow('ZZVB-VM1')['id']);
    [$g] = $grade($hvB, $vm1Ci);
    ok('🔴 a VM whose only possible owner failed is affected, whatever else is in the cluster', $g === 'affected', (string)$g);

    // ---- A person deletes a CI ------------------------------------------------------------------------
    CmdbService::deleteObject($conn, $ctx, $vm2Ci);
    $vm2Virt = (int)$vmRow('ZZVB-VM2-X')['id'];
    ok('deleting a bound CI ends its binding', $q("SELECT end_reason FROM cmdb_object_sources WHERE cmdb_object_id = $vm2Ci") === 'ci_deleted');
    VirtualEstateService::submit($conn, $cid, $report($e, 80));
    ok('🔴 ...and the next report does not recreate it', $ciOf('vm', $vm2Virt) === 0);
    $p = VirtualEstateCmdbService::preview($conn, $cid);
    ok('...it waits in the preview as "CI deleted"', $p['kinds']['vm']['counts']['ci_deleted'] === 1);
    VirtualEstateCmdbService::decide($conn, $ctx, $cid, 'vm', $vm2Virt, 'reset');
    VirtualEstateCmdbService::sync($conn, $cid);
    ok('"Create a new CI" makes one again', $ciOf('vm', $vm2Virt) > 0);

    // ---- An identity review --------------------------------------------------------------------------------
    $vm3Virt = (int)$vmRow('ZZVB-VM3')['id'];
    $copy = $e; $copy['vms']['ZZVB-VM3'] = [veUuid('vm:B-VM3-copy'), 'HV-S', false];
    VirtualEstateService::submit($conn, $cid, $report($copy, 90));
    $newVm3 = (int)$q("SELECT id FROM virt_vms WHERE connection_id = $cid AND name = 'ZZVB-VM3' AND not_seen_since IS NULL");
    ok('🔴 a VM waiting on an identity review gets no CI meanwhile', $newVm3 !== $vm3Virt && $ciOf('vm', $newVm3) === 0);
    $case = (int)$q("SELECT id FROM virt_identity_reviews WHERE connection_id = $cid AND status = 'open' AND old_object_id = $vm3Virt");
    VirtualEstateService::decideReview($conn, $ctx, $case, 'same');
    ok('🔴 "Same machine": the CI the person bound is kept, now on the new identity', $ciOf('vm', $vm3Virt) === $manual
        && $q("SELECT external_id FROM virt_vms WHERE id = $vm3Virt") === veUuid('vm:B-VM3-copy'));

    // ---- Retention never removes a bound object -----------------------------------------------------------------
    $conn->exec("UPDATE virt_vms SET not_seen_since = '2020-01-01 00:00:00' WHERE id = " . (int)$vmRow('ZZVB-VM1')['id']);
    $conn->exec("UPDATE virt_connections SET retention_days = 30 WHERE id = $cid");
    VirtualEstateService::submit($conn, $cid, $report(['vms' => array_diff_key($e['vms'], ['ZZVB-VM1' => 1])] + $e, 100));
    ok('🔴 retention keeps a VM that has a CI', (bool)$vmRow('ZZVB-VM1'));
    ok('...and its CI keeps its relationships (not seen is not gone)', $rel($vm1Ci, $hvB, $s['runs_on_ha_type_id']) === 1);

    // ---- The From discovery card ------------------------------------------------------------------------------------
    $full = ['assets_read' => true]; $none = ['assets_read' => false];
    $card = VirtualEstateCmdbService::forCi($conn, $ctx, $full, $vm2Ci = $ciOf('vm', $vm2Virt));
    ok('the CI shows what it is bound to, read live', ($card['current']['kind'] ?? '') === 'vm' && ($card['current']['host'] ?? '') === 'HV-A', json_encode($card['current']));
    $card = VirtualEstateCmdbService::forCi($conn, $ctx, $none, $vm2Ci);
    ok('🔴 ...restricted for someone who cannot open Assets', $card['current'] === ['restricted' => true]);

    // ---- Unbind ------------------------------------------------------------------------------------------------------
    VirtualEstateCmdbService::decide($conn, $ctx, $cid, 'vm', $vm2Virt, 'unbind');
    VirtualEstateCmdbService::sync($conn, $cid);
    ok('an unbound object is left alone - no new CI on the next sync', $ciOf('vm', $vm2Virt) === 0 && (int)$q("SELECT COUNT(*) FROM cmdb_objects WHERE id = $vm2Ci") === 1);

    // ---- The suggestion reuses only a type that behaves the same ----------------------------------------------
    ok('🔴 the suggested plain "runs on" carries impact from the host to the VM, whatever else is called "runs on"',
        $q("SELECT CONCAT(impact_direction, '/', redundancy) FROM cmdb_relationship_types WHERE id = {$s['runs_on_type_id']}") === 'to_from/none');

    // ---- Deleting the connection ---------------------------------------------------------------------------------
    $live = (int)$q("SELECT COUNT(*) FROM cmdb_object_sources WHERE connection_id = $cid AND ended_at IS NULL");
    $conn->beginTransaction();
    VirtualEstateCmdbService::connectionDeleted($conn, $ctx, $cid);
    $conn->commit();
    ok('🔴 deleting a connection ends every live binding, keeping the rows', $live > 0
        && (int)$q("SELECT COUNT(*) FROM cmdb_object_sources WHERE connection_id = $cid AND ended_at IS NULL") === 0
        && (int)$q("SELECT COUNT(*) FROM cmdb_object_sources WHERE connection_id = $cid AND end_reason = 'connection_deleted'") === $live);
    ok('...its mapping, decisions and managed edges go; the CIs stay', (int)$q("SELECT COUNT(*) FROM virt_cmdb_mappings WHERE connection_id = $cid") === 0
        && (int)$q("SELECT COUNT(*) FROM cmdb_relationship_sources WHERE connection_id = $cid") === 0
        && (int)$q("SELECT COUNT(*) FROM virt_cmdb_decisions WHERE connection_id = $cid") === 0
        && (int)$q("SELECT COUNT(*) FROM cmdb_objects WHERE id = $vm1Ci") === 1);
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
} finally {
    if ($cid) {
        $ids = array_map('intval', $conn->query("SELECT DISTINCT cmdb_object_id FROM cmdb_object_sources WHERE connection_id = $cid")->fetchAll(PDO::FETCH_COLUMN));
        foreach (array_unique(array_merge($ids, $manualCis)) as $id) { try { CmdbService::deleteObject($conn, $ctx, $id); } catch (Throwable) {} }
        foreach (['cmdb_object_sources', 'cmdb_relationship_sources', 'virt_cmdb_decisions', 'virt_cmdb_mappings'] as $t) {
            try { $conn->prepare("DELETE FROM $t WHERE connection_id = ?")->execute([$cid]); } catch (Throwable) {}
        }
        try { $conn->prepare('DELETE FROM job_runs WHERE job_key = ?')->execute(['virt_push_' . $cid]); } catch (Throwable) {}
        try { $conn->prepare("DELETE FROM virt_connections WHERE id = ? AND name LIKE 'ZZVB-%'")->execute([$cid]); } catch (Throwable) {}
    }
    // Only what the suggestion created during this run.
    try { $conn->exec("DELETE FROM cmdb_relationship_types WHERE id > $maxType AND description = 'Made for Hyper-V discovery (GH #199).'"); } catch (Throwable) {}
    try { $conn->exec("DELETE FROM cmdb_classes WHERE id > $maxClass AND description = 'Made for Hyper-V discovery (GH #199).'"); } catch (Throwable) {}
    echo '  cleanup: ' . (int)$conn->query("SELECT COUNT(*) FROM cmdb_objects WHERE name LIKE 'ZZVB%' OR name LIKE 'zzvb%' OR name IN ('CL-ZZ', 'HV-A', 'HV-B', 'HV-S')")->fetchColumn() . ' test CIs, '
        . (int)$conn->query("SELECT COUNT(*) FROM virt_connections WHERE name LIKE 'ZZVB-%'")->fetchColumn() . " test connections left\n";
}
echo str_repeat('-', 70) . "\n  {$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
