<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables and only tidies up if it runs
   to the end. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * The Virtualisation demo (System -> Demo Data): it builds what the walkthrough relies on,
 * and Remove takes away exactly what it made - every table is back to its count before.
 *
 * If the demo was in place when the test started, it is imported again at the end.
 *
 * Run:  php tests/demo-virtualisation.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/demo_virtualisation.php';
require_once __DIR__ . '/../includes/cmdb_impact.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
echo "\nVirtualisation demo data\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ctx = new ActorContext(1, null, 'ui', 'en', 'Administrator');
$q = fn(string $sql) => $conn->query($sql)->fetchColumn();
$tables = ['assets', 'asset_types', 'asset_ci_links', 'asset_history', 'asset_review_items', 'cmdb_objects', 'cmdb_classes',
    'cmdb_relationship_types', 'cmdb_object_relationships', 'cmdb_object_properties', 'cmdb_object_sources', 'cmdb_relationship_sources',
    'virt_connections', 'virt_vms', 'virt_hosts', 'virt_cmdb_mappings', 'virt_cmdb_decisions', 'virt_identity_reviews',
    'proxmox_connections', 'proxmox_nodes', 'proxmox_vms', 'vcloud_connections', 'vcloud_vms', 'servers', 'job_runs'];
$counts = function () use ($conn, $tables): array {
    $o = [];
    foreach ($tables as $t) { $o[$t] = (int)$conn->query("SELECT COUNT(*) FROM $t")->fetchColumn(); }
    return $o;
};

$wasThere = demoVirtStatus($conn)['total'] > 0;
try {
    demoVirtRemove($conn, $ctx);
    $before = $counts();

    $st = demoVirtImport($conn, $ctx);
    ok('every source is there', $st['hyperv_vms'] > 0 && $st['proxmox_vms'] > 0 && $st['vcloud_vms'] > 0 && $st['vcenter_vms'] > 0, json_encode($st));
    $cid = (int)$q('SELECT id FROM virt_connections WHERE is_demo = 1');
    ok('one VM no longer seen, one identity change to review', (int)$q("SELECT COUNT(*) FROM virt_vms WHERE connection_id = $cid AND not_seen_since IS NOT NULL") === 2
        && (int)$q("SELECT COUNT(*) FROM virt_identity_reviews WHERE connection_id = $cid AND status = 'open'") === 1);
    ok('a live migration left two placements on WEB02', (int)$q("SELECT COUNT(*) FROM virt_placements p JOIN virt_vms v ON v.id = p.vm_id WHERE v.connection_id = $cid AND v.name = 'DEMO-WEB02'") === 2);
    ok('the standalone host has no usable UUID, so it is "Name only"', $q("SELECT identity_kind FROM virt_hosts WHERE connection_id = $cid AND name = 'DEMO-HV03'") === 'name');
    $p = VirtualEstateCmdbService::preview($conn, $cid);
    ok('the same-name CI waits to be confirmed', $p['kinds']['vm']['counts']['match'] === 1, json_encode($p['kinds']['vm']['counts']));
    $host = fn(string $n) => (int)$q("SELECT s.cmdb_object_id FROM cmdb_object_sources s JOIN virt_hosts h ON h.id = s.virt_object_id
        WHERE s.object_kind = 'host' AND s.ended_at IS NULL AND h.connection_id = $cid AND h.name = '$n'");
    $grades = function (int $root) use ($conn): array {
        $b = cmdbBlastRadius($conn, $root, cmdbImpactTenantScope($conn, $root));
        return array_column($b['nodes'], 'grade', 'name');
    };
    $g1 = $grades($host('DEMO-HV01')); $g2 = $grades($host('DEMO-HV02'));
    ok('🔴 HV01 fails: the highly available VMs and Payroll may be interrupted', ($g1['DEMO-SQL01'] ?? '') === 'may_be_interrupted' && ($g1['Payroll (demo)'] ?? '') === 'may_be_interrupted', json_encode($g1));
    ok('🔴 HV02 fails: APP01 is not a cluster role, so it and Payroll are affected', ($g2['DEMO-APP01'] ?? '') === 'affected' && ($g2['Payroll (demo)'] ?? '') === 'affected', json_encode($g2));
    ok('five assets linked, two left for "Create linked CI"', (int)$q('SELECT COUNT(*) FROM asset_ci_links WHERE is_demo = 1 AND unlinked_at IS NULL') === 5);
    ok('the two records of one machine are a review case', (int)$q("SELECT COUNT(*) FROM asset_review_items WHERE match_key LIKE 'serial:demo-vm-0031:%' AND status = 'open'") === 1);
    ok('importing again replaces it rather than adding a second copy', demoVirtImport($conn, $ctx) === $st && (int)$q('SELECT COUNT(*) FROM virt_connections WHERE is_demo = 1') === 1);

    // Someone trying it out makes a CI in a demo class and maps one of their own asset types to it.
    $vmClass = (int)$q("SELECT vm_class_id FROM virt_cmdb_mappings WHERE connection_id = (SELECT id FROM virt_connections WHERE is_demo = 1)");
    CmdbService::saveObject($conn, $ctx, ['name' => 'ZZDV made while trying it', 'class_id' => $vmClass]);

    demoVirtRemove($conn, $ctx);
    $after = $counts();
    $diff = array_filter(array_keys($before), fn($t) => $before[$t] !== $after[$t]);
    ok('🔴 Remove puts every table back to its count before', !$diff, json_encode(array_map(fn($t) => [$t, $before[$t], $after[$t]], array_values($diff))));
    ok('...including a CI a person made in a demo class', (int)$q("SELECT COUNT(*) FROM cmdb_objects WHERE name = 'ZZDV made while trying it'") === 0);
    ok('the status says nothing is left', demoVirtStatus($conn)['total'] === 0);
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
} finally {
    try { demoVirtRemove($conn, $ctx); } catch (Throwable) {}
    $conn->exec("DELETE FROM cmdb_objects WHERE name = 'ZZDV made while trying it'");
    if ($wasThere) { demoVirtImport($conn, $ctx); echo "  (the demo was in place before, so it has been imported again)\n"; }
}
echo str_repeat('-', 70) . "\n  {$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
