<?php
/* Never over the web: tests/ is CLI only. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
/**
 * Synthetic Hyper-V estates (GH #199) - for tests, the load test and the example reports in
 * samples/hyperv/. Everything is made up: names like CL-01 / HV-01-03 / VM-01-0042 and
 * deterministic UUIDs derived from those names. No real estate is involved, which is what the
 * reporter asked for ("any shared example would preferably be fully synthetic").
 *
 *   veSyntheticEstate('small'|'medium'|'large')   an estate as plain arrays
 *   veReport($estate, $observedAt, $opts)          the report the collector would send for it
 *
 * Reference scenarios (the design's sizing):
 *   small   3 standalone hosts, 40 VMs
 *   medium  4 clusters of 4 nodes + 6 standalone hosts, 800 VMs
 *   large   20 clusters of 16 nodes, 8,000 VMs
 */

/** A deterministic UUID from a name, so the same synthetic host is the same "machine" every run. */
function veUuid(string $seed): string
{
    $h = md5('freeitsm-synthetic:' . $seed);
    return substr($h, 0, 8) . '-' . substr($h, 8, 4) . '-' . substr($h, 12, 4) . '-' . substr($h, 16, 4) . '-' . substr($h, 20, 12);
}

/**
 * @return array{clusters: array, hosts: array, vms: array}
 *   hosts: name => [uuid, cluster (name or null)]; vms: name => [id, host, clustered]
 */
function veSyntheticEstate(string $size): array
{
    [$clusters, $nodes, $standalone, $vmCount] = match ($size) {
        'small'  => [0, 0, 3, 40],
        'medium' => [4, 4, 6, 800],
        'large'  => [20, 16, 0, 8000],
        default  => throw new InvalidArgumentException("Unknown size $size"),
    };
    $e = ['clusters' => [], 'hosts' => [], 'vms' => []];
    for ($c = 1; $c <= $clusters; $c++) {
        $cn = sprintf('CL-%02d', $c);
        $e['clusters'][$cn] = veUuid("cluster:$cn");
        for ($n = 1; $n <= $nodes; $n++) {
            $hn = sprintf('HV-%02d-%02d', $c, $n);
            $e['hosts'][$hn] = [veUuid("host:$hn"), $cn];
        }
    }
    for ($s = 1; $s <= $standalone; $s++) {
        $hn = sprintf('HV-S%02d', $s);
        $e['hosts'][$hn] = [veUuid("host:$hn"), null];
    }
    $hostNames = array_keys($e['hosts']);
    for ($v = 1; $v <= $vmCount; $v++) {
        $host = $hostNames[($v - 1) % count($hostNames)];
        $vn = sprintf('VM-%05d', $v);
        // In a cluster, four in five VMs are highly available roles.
        $e['vms'][$vn] = [veUuid("vm:$vn"), $host, $e['hosts'][$host][1] !== null && $v % 5 !== 0];
    }
    return $e;
}

/**
 * The report for an estate.
 *
 * @param array $opts  scope ('all' or a list of [kind, name]), unreadable ([kind, name] list),
 *                     sections (names to include, default all), complete (bool, default true),
 *                     disks (bool, default true)
 */
function veReport(array $e, string $observedAt, array $opts = []): array
{
    $want = $opts['sections'] ?? ['clusters', 'hosts', 'cluster_roles', 'vms', 'vm_disks', 'vm_networks'];
    $complete = $opts['complete'] ?? true;
    $unreadable = [];
    foreach ($opts['unreadable'] ?? [] as [$kind, $name]) { $unreadable[] = ['kind' => $kind, 'name' => $name, 'error' => 'Synthetic: access denied']; }
    $skipHosts = array_column(array_filter($unreadable, fn($u) => $u['kind'] === 'host'), 'name');
    $scope = [];
    if (($opts['scope'] ?? 'all') === 'all') {
        foreach ($e['clusters'] as $cn => $id) { $scope[] = ['kind' => 'cluster', 'id' => $id, 'name' => $cn]; }
        foreach ($e['hosts'] as $hn => [$u, $cl]) { if ($cl === null) { $scope[] = ['kind' => 'host', 'id' => $u, 'name' => $hn]; } }
    } else {
        foreach ($opts['scope'] as [$kind, $name]) {
            $scope[] = $kind === 'cluster' ? ['kind' => 'cluster', 'id' => $e['clusters'][$name], 'name' => $name] : ['kind' => 'host', 'id' => $e['hosts'][$name][0], 'name' => $name];
        }
    }
    $inScope = function (string $host) use ($e, $scope): bool {
        foreach ($scope as $s) {
            if ($s['kind'] === 'host' && $s['name'] === $host) { return true; }
            if ($s['kind'] === 'cluster' && $e['hosts'][$host][1] === $s['name']) { return true; }
        }
        return false;
    };
    $sec = [];
    $clusterNodes = [];
    foreach ($e['hosts'] as $hn => [, $cl]) { if ($cl !== null) { $clusterNodes[$cl][] = $hn; } }
    if (in_array('clusters', $want, true)) {
        $sec['clusters'] = ['complete' => $complete, 'items' => []];
        foreach ($e['clusters'] as $cn => $id) {
            if (!array_filter($scope, fn($s) => $s['kind'] === 'cluster' && $s['name'] === $cn)) { continue; }
            $sec['clusters']['items'][] = ['id' => $id, 'name' => $cn, 'quorum_type' => 'NodeAndFileShareMajority', 'witness' => '\\\\witness\\' . strtolower($cn)];
        }
    }
    if (in_array('hosts', $want, true)) {
        $sec['hosts'] = ['complete' => $complete, 'items' => []];
        foreach ($e['hosts'] as $hn => [$u, $cl]) {
            if (!$inScope($hn) || in_array($hn, $skipHosts, true)) { continue; }
            $sec['hosts']['items'][] = ['id' => $u, 'name' => $hn, 'cluster_id' => $cl !== null ? $e['clusters'][$cl] : null,
                'node_state' => $cl !== null ? 'Up' : null, 'os_version' => 'Windows Server 2022 Datacenter', 'cpu_count' => 64, 'memory_bytes' => 512 * 1024 ** 3];
        }
    }
    $roles = [];
    $vms = [];
    foreach ($e['vms'] as $vn => [$id, $host, $ha]) {
        if (!$inScope($host) || in_array($host, $skipHosts, true)) { continue; }
        $cl = $e['hosts'][$host][1];
        $roleId = $ha ? veUuid("role:$vn") : null;
        if ($ha) { $roles[] = ['id' => $roleId, 'name' => $vn, 'cluster_id' => $e['clusters'][$cl], 'role_type' => 'VirtualMachine', 'owner_host' => $host, 'state' => 'Online', 'preferred_owners' => []]; }
        $vms[] = ['id' => $id, 'name' => $vn, 'host' => $host, 'state' => 'Running', 'is_clustered' => $ha, 'cluster_role_id' => $roleId,
            'possible_owners' => $ha ? $clusterNodes[$cl] : [], 'generation' => 2, 'cpu_count' => 4, 'memory_bytes' => 8 * 1024 ** 3,
            'guest_addresses' => ['10.' . (crc32($vn) % 250) . '.0.' . (crc32($vn . 'x') % 250 + 1)]];
    }
    if (in_array('cluster_roles', $want, true)) { $sec['cluster_roles'] = ['complete' => $complete, 'items' => $roles]; }
    if (in_array('vms', $want, true)) { $sec['vms'] = ['complete' => $complete, 'items' => $vms]; }
    if (in_array('vm_disks', $want, true) && ($opts['disks'] ?? true)) {
        $sec['vm_disks'] = ['complete' => $complete, 'items' => array_map(fn($v) => ['vm_id' => $v['id'], 'id' => 'scsi-0-0',
            'path' => 'C:\\ClusterStorage\\Volume1\\' . $v['name'] . '\\disk0.vhdx', 'size_bytes' => 127 * 1024 ** 3, 'used_bytes' => 40 * 1024 ** 3, 'controller' => 'SCSI 0:0'], $vms)];
    }
    if (in_array('vm_networks', $want, true) && ($opts['disks'] ?? true)) {
        $sec['vm_networks'] = ['complete' => $complete, 'items' => array_map(fn($v) => ['vm_id' => $v['id'], 'id' => 'nic-0', 'name' => 'Network Adapter',
            'switch_name' => 'vSwitch-Prod', 'mac_address' => strtoupper(substr(md5($v['name']), 0, 12)), 'ip_addresses' => $v['guest_addresses'], 'vlan_id' => 20], $vms)];
    }
    return ['schema_version' => 1, 'observed_at' => $observedAt, 'scope' => $scope,
        'collector' => ['name' => 'Invoke-HyperVInventory', 'version' => 'synthetic', 'powershell' => '5.1', 'host' => 'MGMT-SYNTHETIC'],
        'unreadable' => $unreadable, 'sections' => $sec];
}
