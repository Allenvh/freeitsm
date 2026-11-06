<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/**
 * GH #194 Part B: REST inventory reports are observations from a NAMED source - not a
 * heartbeat, not ownership of the asset, never another source's rows.
 *
 * Real HTTP with real keys against FREEITSM_TEST_URL (the site using this database).
 * Every fixture is made here and deleted by id in `finally`: two ZZ assets written
 * directly, two ZZ applications already in the catalogue (so nothing is "discovered"
 * and no workflow fires), one ZZ analyst, three keys.
 *
 * Contributed by @Dr4inder (PR #197); rewritten in review for per-company source
 * ownership, per-section partial success and the design's field names.
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/services/asset_inventory.php';
require_once __DIR__ . '/../api/v1/lib/openapi.php';
$base = rtrim(getenv('FREEITSM_TEST_URL') ?: '', '/');
if (!$base) { fwrite(STDERR, "Set FREEITSM_TEST_URL to the matching development site.\n"); exit(1); }
$conn = connectToDatabase();
$prefix = 'zz_inv_' . bin2hex(random_bytes(4));
$assetIds = []; $appIds = []; $keyIds = []; $otherAnalyst = 0;
$sources = ['synth', 'other'];
$pass = 0; $fail = 0;
$ok = function (string $label, bool $cond, string $why = '') use (&$pass, &$fail): void {
    if ($cond) { ++$pass; echo "PASS $label\n"; } else { ++$fail; echo "FAIL $label" . ($why !== '' ? "  [$why]" : '') . "\n"; }
};
$actor = (int)$conn->query('SELECT MIN(id) FROM analysts WHERE is_admin = 1 AND is_active = 1')->fetchColumn();
try {
    $api = apiV1BuildOpenApi();
    $ok('OpenAPI publishes the report body', ($api['paths']['/assets/{id}/inventory']['post']['requestBody']['content']['application/json']['schema']['$ref'] ?? '') === '#/components/schemas/AssetInventorySnapshot');
    $ok('...the unlink operation', isset($api['paths']['/assets/{id}/inventory/{source}']['delete']));
    $ok('...and per-row provenance on the inventory lists', isset($api['components']['schemas']['AssetDisk']['properties']['observed_at'],
        $api['components']['schemas']['AssetNetworkAdapter']['properties']['source'], $api['components']['schemas']['AssetSoftware']['properties']['imported_at']));

    $tenant = getDefaultTenantId($conn);
    foreach (['a', 'b'] as $s) {
        $conn->prepare('INSERT INTO assets (hostname, tenant_id) VALUES (?, ?)')->execute([$prefix . $s, $tenant]);
        $assetIds[] = (int)$conn->lastInsertId();
    }
    [$id, $id2] = $assetIds;
    foreach (['app1', 'app2'] as $s) {
        $conn->prepare("INSERT INTO software_inventory_apps (display_name, publisher, source) VALUES (?, 'ZZ', 'manual')")->execute([$prefix . $s]);
        $appIds[] = (int)$conn->lastInsertId();
    }
    // Another source's rows the reports must never touch.
    $conn->prepare("INSERT INTO asset_disks (asset_id, drive, source) VALUES (?, 'M:', 'manual')")->execute([$id]);
    $conn->prepare("INSERT INTO software_inventory_detail (host_id, app_id, display_version, source) VALUES (?, ?, 'manual-1', 'manual')")->execute([$id, $appIds[0]]);

    $conn->prepare('INSERT INTO analysts (username, password_hash, full_name, email, is_active, is_admin, can_access_all_modules) VALUES (?, ?, ?, ?, 1, 0, 1)')
        ->execute([$prefix, password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT), $prefix, $prefix . '@example.invalid']);
    $otherAnalyst = (int)$conn->lastInsertId();
    $keys = [];
    $mkKey = function (string $kind, int $analyst, array $perms) use ($conn, $prefix, &$keys, &$keyIds) {
        $keys[$kind] = 'fitsm_' . bin2hex(random_bytes(24));
        $conn->prepare('INSERT INTO api_keys (name, key_prefix, key_hash, analyst_id, permissions) VALUES (?, ?, ?, ?, ?)')
            ->execute([$prefix . $kind, substr($keys[$kind], 0, 12), hash('sha256', $keys[$kind]), $analyst, json_encode($perms)]);
        $keyIds[] = (int)$conn->lastInsertId();
    };
    $full = ['asset_inventory' => ['read', 'create', 'delete']];
    $mkKey('write', $actor, $full);
    $mkKey('rotated', $actor, $full);           // the same integration, a new key
    $mkKey('read', $actor, ['asset_inventory' => ['read']]);
    $mkKey('rival', $otherAnalyst, $full);      // another integration

    $call = function (string $method, string $path, string $kind, ?array $body = null) use ($base, &$keys): array {
        $ch = curl_init($base . '/api/v1/index.php?path=' . rawurlencode($path));
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 20, CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'Authorization: Bearer ' . $keys[$kind]]]
            + ($body !== null ? [CURLOPT_POSTFIELDS => json_encode($body)] : []));
        $raw = curl_exec($ch);
        if ($raw === false) { throw new RuntimeException('HTTP failed: ' . curl_error($ch)); }
        return [(int)curl_getinfo($ch, CURLINFO_HTTP_CODE), json_decode($raw, true)];
    };
    $post = fn(int $asset, array $body, string $kind = 'write') => $call('POST', "/assets/$asset/inventory", $kind, $body);
    $count = function (string $table, int $asset, ?string $source = null) use ($conn): int {
        $col = $table === 'software_inventory_detail' ? 'host_id' : 'asset_id';
        $q = $conn->prepare("SELECT COUNT(*) FROM $table WHERE $col = ?" . ($source ? ' AND source = ?' : ''));
        $q->execute($source ? [$asset, $source] : [$asset]);
        return (int)$q->fetchColumn();
    };
    $at = fn(int $agoSeconds) => gmdate('Y-m-d\TH:i:s\Z', time() - $agoSeconds);
    $report = fn(string $observed, array $sections, string $source = 'synth', ?string $ref = null) =>
        ['schema_version' => 1, 'source' => $source, 'source_device_ref' => $ref ?? $prefix . '-dev', 'observed_at' => $observed, 'sections' => $sections];
    $sw = fn(bool $complete, array $names) => ['complete' => $complete, 'items' => array_map(fn($n) => ['display_name' => $prefix . $n, 'publisher' => 'ZZ', 'display_version' => '1.0'], $names)];
    $full1 = $report($at(86400), [
        'software' => $sw(true, ['app1']),
        'disks' => ['complete' => true, 'items' => [['external_id' => 'c', 'drive' => 'C:', 'size_bytes' => 100, 'free_bytes' => 40]]],
        'network_adapters' => ['complete' => true, 'items' => [['external_id' => 'eth0', 'name' => 'Ethernet', 'mac_address' => '00:11:22:33:44:55', 'ip_address' => '192.0.2.10']]],
    ]);
    $rest = AssetInventoryService::storedSource('synth');
    $core = fn() => $conn->query("SELECT hostname, last_seen, service_tag FROM assets WHERE id = $id")->fetch(PDO::FETCH_ASSOC);
    $coreBefore = $core();

    echo "\nPermissions and envelope\n";
    [$code] = $post($id, $full1, 'read');
    $ok('a key with only asset_inventory.read cannot report', $code === 403, "$code");
    [$code, $r] = $post($id, $report($at(60), ['disks' => ['complete' => true, 'items' => []]], 'agent'));
    $ok('a built-in source name (agent) is refused', $code === 422 && ($r['error']['code'] ?? '') === 'invalid_field', "$code");
    [$code] = $post($id, ['schema_version' => 1, 'source' => 'synth', 'source_device_ref' => 'x', 'observed_at' => $at(60), 'sections' => ['printers' => []]]);
    $ok('an unknown section refuses the whole report', $code === 422, "$code");

    echo "\nFirst report\n";
    [$code, $r] = $post($id, $full1);
    $s = $r['data']['sections'] ?? [];
    $ok('applied, each section with its counts', $code === 200 && ($s['software']['result'] ?? '') === 'applied' && ($s['software']['added'] ?? -1) === 1
        && ($s['disks']['added'] ?? -1) === 1 && ($s['network_adapters']['added'] ?? -1) === 1, $code . ' ' . json_encode($r));
    $ok('...beside the manual rows, which are untouched', $count('asset_disks', $id) === 2 && $count('software_inventory_detail', $id) === 2);
    $ok('...the same application from two sources on one device (the old unique key refused this)', $count('software_inventory_detail', $id, 'manual') === 1 && $count('software_inventory_detail', $id, $rest) === 1);
    $ok('...last_seen, hostname and serial unchanged - an import is not a heartbeat', $core() === $coreBefore);
    $own = $conn->prepare('SELECT owner_analyst_id FROM asset_inventory_source_owners WHERE tenant_id = ? AND source = ?');
    $own->execute([$tenant, 'synth']);
    $ok('...and the source name now belongs to the key\'s analyst', (int)$own->fetchColumn() === $actor);
    $diskId = (int)$conn->query("SELECT id FROM asset_disks WHERE asset_id = $id AND source = " . $conn->quote($rest))->fetchColumn();

    echo "\nRetries, order and time\n";
    [$code, $r] = $post($id, $full1);
    $ok('the same report again: every section replayed, nothing duplicated', $code === 200 && array_unique(array_column($r['data']['sections'] ?? [], 'result')) === ['replayed']
        && $count('asset_disks', $id) === 2);
    [$code, $r] = $post($id, $full1, 'rotated');
    $ok('a ROTATED key for the same analyst still owns the source', $code === 200, "$code");
    $diff = $full1; $diff['sections'] = ['disks' => $full1['sections']['disks']]; $diff['sections']['disks']['items'][0]['label'] = 'changed';
    [$code, $r] = $post($id, $diff);
    $ok('the same time with different content fails that section (observation_conflict)', $code === 422 && ($r['error']['details']['disks']['error']['code'] ?? '') === 'observation_conflict', $code . ' ' . json_encode($r));
    $newer = $report($at(3600), ['disks' => ['complete' => false, 'items' => [['external_id' => 'c', 'drive' => 'C:', 'size_bytes' => 100, 'free_bytes' => 10], ['external_id' => 'd', 'drive' => 'D:']]]]);
    [$code, $r] = $post($id, $newer);
    $ok('a partial section adds and updates, removes nothing', $code === 200 && ($r['data']['sections']['disks']['added'] ?? -1) === 1 && ($r['data']['sections']['disks']['updated'] ?? -1) === 1
        && $count('asset_disks', $id) === 3, $code . ' ' . json_encode($r));
    $ok('...an updated row keeps its id', (int)$conn->query("SELECT id FROM asset_disks WHERE asset_id = $id AND source = " . $conn->quote($rest) . " AND external_id = 'c'")->fetchColumn() === $diskId);
    [$code, $r] = $post($id, $full1);
    $ok('an OLDER report is stale for that section, and says what is newer', $code === 200 && ($r['data']['sections']['disks']['result'] ?? '') === 'stale'
        && isset($r['data']['sections']['disks']['newer_observed_at']) && $count('asset_disks', $id) === 3);
    $ok('...while its other sections (not newer) are judged on their own - replayed', ($r['data']['sections']['software']['result'] ?? '') === 'replayed');

    echo "\nPartial success\n";
    $mixed = $report($at(1800), [
        'software' => ['complete' => true, 'items' => [['display_name' => $prefix . 'app2', 'publisher' => 'ZZ', 'system_component' => 'yes']]],
        'network_adapters' => ['complete' => true, 'items' => [['external_id' => 'eth0', 'name' => 'Ethernet 2']]],
    ]);
    [$code, $r] = $post($id, $mixed);
    $ok('one bad section, one good: 207, the good one applied', $code === 207 && ($r['data']['sections']['network_adapters']['result'] ?? '') === 'applied'
        && ($r['data']['sections']['software']['result'] ?? '') === 'failed', $code . ' ' . json_encode($r));
    $ok('...the failed one changed nothing', $count('software_inventory_detail', $id, $rest) === 1
        && (int)$conn->query("SELECT COUNT(*) FROM software_inventory_detail WHERE host_id = $id AND app_id = {$appIds[1]}")->fetchColumn() === 0);
    // GH #200 Part B: the failure is kept, beside the last good data and its time.
    $swState = fn() => $conn->query("SELECT x.last_outcome, x.last_error, x.observed_at FROM asset_inventory_sections x JOIN asset_inventory_sources s ON s.id = x.inventory_source_id
        WHERE s.asset_id = $id AND s.source = 'synth' AND x.section_name = 'software'")->fetch(PDO::FETCH_ASSOC) ?: [];
    $before = $swState();
    $ok('...and is RECORDED as failed, with its error, the last good observation untouched', ($before['last_outcome'] ?? '') === 'failed'
        && ($before['last_error'] ?? '') !== '' && $before['observed_at'] !== null && $before['observed_at'] < str_replace('T', ' ', rtrim($mixed['observed_at'], 'Z')), json_encode($before));
    $mixed['sections']['software']['items'][0]['system_component'] = false;
    [$code, $r] = $post($id, $mixed);
    $ok('send it again, fixed: the failed section applies, the applied one is replayed', $code === 200 && ($r['data']['sections']['software']['result'] ?? '') === 'applied'
        && ($r['data']['sections']['network_adapters']['result'] ?? '') === 'replayed', $code . ' ' . json_encode($r));
    $ok('...and the section is recorded as succeeded again, its error cleared', ($swState()['last_outcome'] ?? '') === 'succeeded' && $swState()['last_error'] === null);
    $ok('...software now exactly app2 for this source (complete removed app1)', ($r['data']['sections']['software']['removed'] ?? -1) === 1
        && (int)$conn->query("SELECT app_id FROM software_inventory_detail WHERE host_id = $id AND source = " . $conn->quote($rest))->fetchColumn() === $appIds[1]);

    echo "\nOwnership and identity\n";
    [$code, $r] = $post($id, $report($at(30), ['disks' => ['complete' => true, 'items' => []]]), 'rival');
    $ok('another integration cannot write (or clear) this source by sending its name', $code === 403 && ($r['error']['code'] ?? '') === 'source_owner', "$code");
    $ok('...even on an asset this source has not reached yet', $post($id2, $report($at(30), ['disks' => ['complete' => true, 'items' => []]], 'synth', 'elsewhere'), 'rival')[0] === 403);
    [$code] = $post($id, $report($at(1200), ['disks' => ['complete' => true, 'items' => [['external_id' => 'x', 'drive' => 'X:']]]], 'other'), 'rival');
    $ok('it CAN report under its own name, beside the first', $code === 200 && $count('asset_disks', $id) === 4, "$code");
    [$code, $r] = $post($id2, $full1);
    $ok('the same device on another asset is refused, naming the asset', $code === 409 && ($r['error']['code'] ?? '') === 'device_ref_conflict' && str_contains($r['error']['message'] ?? '', (string)$id), $code . ' ' . json_encode($r));
    [$code, $r] = $post($id, $report($at(1100), ['disks' => ['complete' => true, 'items' => []]], 'synth', 'a-different-device'));
    $ok('...and this asset cannot quietly switch to another device of the source', $code === 409, "$code");
    [$code] = $post($id, $report($at(600), ['disks' => ['complete' => true, 'items' => []]]));
    $ok('a complete EMPTY section removes all of this source\'s rows - only this source\'s', $code === 200 && $count('asset_disks', $id, $rest) === 0 && $count('asset_disks', $id) === 2);

    echo "\nProvenance and unlink\n";
    [$code, $r] = $call('GET', "/assets/$id/inventory", 'read');
    $srcs = array_column($r['data'] ?? [], null, 'source');
    $ok('GET lists both sources with their owners and section times', $code === 200 && isset($srcs['synth']['sections']['disks']['observed_at'], $srcs['other'])
        && ($srcs['synth']['owner_analyst_id'] ?? 0) === $actor && ($srcs['other']['owner_analyst_id'] ?? 0) === $otherAnalyst);
    [$code] = $call('DELETE', "/assets/$id/inventory/synth", 'rival');
    $ok('another integration cannot unlink this source', $code === 403, "$code");
    [$code] = $call('DELETE', "/assets/$id/inventory/synth", 'read');
    $ok('...nor can a read-only key', $code === 403, "$code");
    [$code, $r] = $call('DELETE', "/assets/$id/inventory/synth", 'write');
    $ok('the owner unlinks: its rows go, the others stay', $code === 200 && $count('software_inventory_detail', $id, $rest) === 0 && $count('asset_network_adapters', $id, $rest) === 0
        && $count('software_inventory_detail', $id, 'manual') === 1 && $count('asset_disks', $id, AssetInventoryService::storedSource('other')) === 1, $code . ' ' . json_encode($r));
    [$code] = $post($id2, $full1);
    $ok('...and the device can now be linked to the other asset', $code === 200, "$code");
    $hist = (int)$conn->query("SELECT COUNT(*) FROM asset_history WHERE asset_id = $id AND field_name = 'inventory_import'")->fetchColumn();
    $ok('imports and the unlink are in the asset history', $hist >= 5, "$hist");
} finally {
    if ($conn->inTransaction()) { $conn->rollBack(); }
    foreach ($assetIds as $a) {
        foreach (['software_inventory_detail' => 'host_id', 'asset_disks' => 'asset_id', 'asset_network_adapters' => 'asset_id'] as $t => $c) {
            $conn->prepare("DELETE FROM $t WHERE $c = ?")->execute([$a]);
        }
        $conn->prepare('DELETE s FROM asset_inventory_sections s JOIN asset_inventory_sources b ON b.id = s.inventory_source_id WHERE b.asset_id = ?')->execute([$a]);
        $conn->prepare('DELETE FROM asset_inventory_sources WHERE asset_id = ?')->execute([$a]);
        $conn->prepare('DELETE FROM asset_history WHERE asset_id = ?')->execute([$a]);
        $conn->prepare('DELETE FROM assets WHERE id = ?')->execute([$a]);
    }
    // The source names were claimed by the test analysts only if nobody owned them before.
    if (isset($tenant)) {
        foreach ($sources as $src) {
            $conn->prepare('DELETE FROM asset_inventory_source_owners WHERE tenant_id = ? AND source = ? AND (owner_analyst_id = ? OR owner_analyst_id = ?)
                AND NOT EXISTS (SELECT 1 FROM asset_inventory_sources s WHERE s.tenant_id = asset_inventory_source_owners.tenant_id AND s.source = asset_inventory_source_owners.source)')
                ->execute([$tenant, $src, $actor, $otherAnalyst]);
        }
    }
    foreach ($appIds as $app) { $conn->prepare('DELETE FROM software_inventory_apps WHERE id = ?')->execute([$app]); }
    foreach ($keyIds as $k) {
        $conn->prepare('DELETE FROM api_key_rate_limits WHERE api_key_id = ?')->execute([$k]);
        $conn->prepare('DELETE FROM api_keys WHERE id = ?')->execute([$k]);
    }
    if ($otherAnalyst) { $conn->prepare('DELETE FROM analysts WHERE id = ?')->execute([$otherAnalyst]); }
}
echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
