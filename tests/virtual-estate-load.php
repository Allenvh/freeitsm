<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables and only tidies up if it runs
   to the end. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * GH #199: sizing - how long a Hyper-V report takes to apply, on the three synthetic
 * reference estates (tests/fixtures/virtual-estate/synthetic.php). The reporter asked for
 * synthetic scenarios rather than real counts; these are them.
 *
 * For each size: the first report (everything new), then a report where one VM in ten has
 * live-migrated (the everyday case), then the same report again (a replay). Also the time to
 * read every VM back as the Servers page does. Prints a table; fails only if a report does
 * not apply, so it can run anywhere without flaking on a slow box.
 *
 * Run:  php tests/virtual-estate-load.php [small|medium|large ...]   (default: small medium large)
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/services/virtual_estate.php';
require_once __DIR__ . '/fixtures/virtual-estate/synthetic.php';

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$sizes = array_slice($argv, 1) ?: ['small', 'medium', 'large'];
$fail = 0;
$rows = [];
$ids = [];
$ms = fn(float $t) => (int)round((microtime(true) - $t) * 1000);

try {
    foreach ($sizes as $size) {
        $e = veSyntheticEstate($size);
        $conn->prepare("INSERT INTO virt_connections (name, source_kind, created_datetime) VALUES (?, 'hyperv', UTC_TIMESTAMP())")
            ->execute(['ZZVL-' . $size . '-' . bin2hex(random_bytes(2))]);
        $cid = $ids[] = (int)$conn->lastInsertId();
        $t0 = time() - 7200;
        $first = veReport($e, gmdate('Y-m-d\TH:i:s\Z', $t0));
        $bytes = strlen(json_encode($first));

        $t = microtime(true);
        $r1 = VirtualEstateService::submit($conn, $cid, $first);
        $tFirst = $ms($t);

        // One VM in ten live-migrates to the next node of its cluster (or stays, if standalone).
        $hosts = array_keys($e['hosts']);
        $i = 0;
        foreach ($e['vms'] as $vn => [$id, $host, $ha]) {
            if (++$i % 10 !== 0 || $e['hosts'][$host][1] === null) { continue; }
            $peers = array_values(array_filter($hosts, fn($h) => $e['hosts'][$h][1] === $e['hosts'][$host][1] && $h !== $host));
            if ($peers) { $e['vms'][$vn][1] = $peers[0]; }
        }
        $second = veReport($e, gmdate('Y-m-d\TH:i:s\Z', $t0 + 900));
        $t = microtime(true);
        $r2 = VirtualEstateService::submit($conn, $cid, $second);
        $tSecond = $ms($t);

        $t = microtime(true);
        $r3 = VirtualEstateService::submit($conn, $cid, $second);
        $tReplay = $ms($t);

        $t = microtime(true);
        $n = count($conn->query("SELECT v.id, v.name, v.state, h.name AS host, c.name AS cluster FROM virt_vms v
            LEFT JOIN virt_hosts h ON h.id = v.host_id LEFT JOIN virt_clusters c ON c.id = v.cluster_id WHERE v.connection_id = $cid")->fetchAll());
        $tRead = $ms($t);

        $ok = $r1['outcome'] === 'applied' && $r2['outcome'] === 'applied' && $r3['outcome'] === 'replayed' && $n === count($e['vms']);
        if (!$ok) { $fail++; }
        $rows[] = [$size, count($e['clusters']), count($e['hosts']), count($e['vms']), round($bytes / 1024 / 1024, 1) . ' MB', $tFirst, $tSecond, $tReplay, $tRead, $ok ? 'ok' : 'FAIL'];
        echo "  $size done\n";
    }
} finally {
    foreach ($ids as $id) {
        try { $conn->prepare("DELETE FROM job_runs WHERE job_key = ?")->execute(['virt_push_' . $id]); } catch (Throwable) {}
        try { $conn->prepare("DELETE FROM virt_connections WHERE id = ? AND name LIKE 'ZZVL-%'")->execute([$id]); } catch (Throwable) {}
    }
}

printf("\n%-7s %8s %6s %6s %8s %12s %12s %10s %10s %s\n", 'size', 'clusters', 'hosts', 'VMs', 'report', 'first ms', 'migrate ms', 'replay ms', 'read ms', '');
foreach ($rows as $r) { printf("%-7s %8d %6d %6d %8s %12d %12d %10d %10d %s\n", ...$r); }
echo "\n" . ($fail ? "$fail size(s) FAILED\n" : "all applied\n");
exit($fail ? 1 : 0);
