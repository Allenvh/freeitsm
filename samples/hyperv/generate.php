<?php
/**
 * Writes the example Hyper-V reports in this folder (GH #199). Fully synthetic - made by
 * tests/fixtures/virtual-estate/synthetic.php, so no real estate is involved. They show the
 * report scripts/Invoke-HyperVInventory.ps1 sends, and can be posted to a test install:
 *
 *   curl -X POST -H "Authorization: Bearer fitsm_..." -H "Content-Type: application/json" \
 *        --data @small-estate.json "https://itsm.example.com/api/v1/index.php?path=/virtual-estate/1/reports"
 *
 * Run:  php samples/hyperv/generate.php
 */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require __DIR__ . '/../../tests/fixtures/virtual-estate/synthetic.php';

$t = '2026-10-26T09:00:00Z';
$write = function (string $name, array $report) {
    file_put_contents(__DIR__ . "/$name", json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
    echo "wrote $name\n";
};
// Three standalone hosts and 40 VMs.
$write('small-estate.json', veReport(veSyntheticEstate('small'), $t));
// One cluster of a medium estate, as -ReportPer Cluster sends it, with one node unreadable.
$e = veSyntheticEstate('medium');
$e['vms'] = array_filter($e['vms'], fn($v) => str_starts_with($v[1], 'HV-01-'));
$write('one-cluster-one-node-unreadable.json', veReport($e, $t, ['scope' => [['cluster', 'CL-01']], 'unreadable' => [['host', 'HV-01-04']]]));
