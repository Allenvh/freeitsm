<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables and only tidies up if it runs
   to the end. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * GH #199 Part C: redundancy-aware impact grades, on CIs drawn by hand.
 *
 * The design's example, built with three relationship types: "is a member of"
 * (membership), "runs on (highly available)" (placement) and an ordinary "depends on".
 *
 *   CL ── NodeA, NodeB are members        VM1 runs on (HA) NodeA     VM2 depends on NodeA
 *   App1 depends on VM1                   App2 depends on VM1 AND VM2
 *   NodeA, NodeB depend on Storage
 *
 * Checks the four grades, the path rule (a possible failover never softens a hard
 * dependency; unknown is never hidden), the limits always listed, the strict "treat as
 * unknown" setting, a cluster that loses every member, and that with no such types the
 * answer is exactly what it was before (everything affected). Everything is ZZRG-prefixed
 * and removed in `finally`.
 *
 * Run:  php tests/cmdb-impact-grades.php
 */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/tenancy.php';
require_once __DIR__ . '/../includes/cmdb_impact.php';
require_once __DIR__ . '/../includes/services/cmdb.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}
echo "\nRedundancy-aware impact grades (GH #199 Part C)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$ctx = new ActorContext(1, null, 'ui', 'en', 'Administrator');
$cis = []; $types = [];
$setting = $conn->query("SELECT setting_value FROM system_settings WHERE setting_key = 'cmdb_impact_unknown_members'")->fetchColumn();

try {
    $classId = (int)$conn->query("SELECT c.id FROM cmdb_classes c WHERE c.is_active = 1 AND NOT EXISTS (SELECT 1 FROM cmdb_class_properties p
        WHERE p.class_id = c.id AND p.is_required = 1 AND p.property_type = 'object_ref') ORDER BY c.id LIMIT 1")->fetchColumn();
    $req = [];
    foreach ($conn->query("SELECT id, property_key, property_type FROM cmdb_class_properties WHERE class_id = $classId AND is_required = 1")->fetchAll(PDO::FETCH_ASSOC) as $p) {
        $o = $conn->prepare('SELECT option_value FROM cmdb_class_property_options WHERE property_id = ? LIMIT 1');
        $o->execute([$p['id']]);
        $req[$p['property_key']] = match ($p['property_type']) { 'number' => 1, 'date' => gmdate('Y-m-d'), 'boolean' => false, 'dropdown' => $o->fetchColumn() ?: 'x', default => 'ZZRG' };
    }
    $ci = function (string $name) use ($conn, $ctx, $classId, $req, &$cis): int {
        return $cis[$name] = (int)CmdbService::saveObject($conn, $ctx, ['name' => "ZZRG $name", 'class_id' => $classId, 'properties' => $req])['id'];
    };
    $type = function (string $verb, string $direction, string $redundancy) use ($conn, &$types): int {
        $conn->prepare("INSERT INTO cmdb_relationship_types (verb, inverse_verb, impact_direction, redundancy, is_active) VALUES (?, ?, ?, ?, 1)")
            ->execute(["ZZRG $verb " . bin2hex(random_bytes(2)), "ZZRG inverse $verb " . bin2hex(random_bytes(2)), $direction, $redundancy]);
        return $types[] = (int)$conn->lastInsertId();
    };
    $member = $type('is a member of', 'from_to', 'membership');
    $placed = $type('runs on (HA)', 'to_from', 'placement');
    $depends = $type('depends on', 'to_from', 'none');
    $rel = fn(int $from, int $to, int $t) => CmdbService::createRelationship($conn, $ctx, $from, ['to_object_id' => $to, 'relationship_type_id' => $t]);

    foreach (['CL', 'NodeA', 'NodeB', 'VM1', 'VM2', 'App1', 'App2', 'Storage'] as $n) { $ci($n); }
    $rel($cis['NodeA'], $cis['CL'], $member);
    $rel($cis['NodeB'], $cis['CL'], $member);
    $rel($cis['VM1'], $cis['NodeA'], $placed);
    $rel($cis['VM2'], $cis['NodeA'], $depends);
    $rel($cis['App1'], $cis['VM1'], $depends);
    $rel($cis['App2'], $cis['VM1'], $depends);
    $rel($cis['App2'], $cis['VM2'], $depends);
    $rel($cis['NodeA'], $cis['Storage'], $depends);
    $rel($cis['NodeB'], $cis['Storage'], $depends);

    $grades = function (string $root) use ($conn, $cis): array {
        $b = cmdbBlastRadius($conn, $cis[$root], cmdbImpactTenantScope($conn, $cis[$root]));
        $g = [];
        foreach ($b['nodes'] as $n) { $g[substr($n['name'], 5)] = $n; }
        return [$g, $b];
    };

    // ---- NodeA fails ---------------------------------------------------------------------------
    $conn->exec("DELETE FROM system_settings WHERE setting_key = 'cmdb_impact_unknown_members'");
    [$g, $b] = $grades('NodeA');
    $is = fn(string $n, string $grade) => ($g[$n]['grade'] ?? null) === $grade;
    ok('the cluster loses one of two nodes: redundancy reduced', $is('CL', 'redundancy_reduced'), json_encode($g['CL']['grade'] ?? null));
    ok('the highly available VM on it: may be interrupted', $is('VM1', 'may_be_interrupted'));
    ok('🔴 the VM that merely runs on it (not a clustered role): affected', $is('VM2', 'affected'));
    ok('an application on the HA VM: may be interrupted, by way of VM1', $is('App1', 'may_be_interrupted') && ($g['App1']['grade_via']['name'] ?? '') === 'ZZRG VM1');
    ok('🔴 a hard dependency is never softened by a possible failover: App2 needs VM2 too, so affected', $is('App2', 'affected'));
    ok('the result says redundancy rules were used, with counts', $b['grades_used'] === true && $b['grade_counts']['may_be_interrupted'] === 2, json_encode($b['grade_counts']));
    $codes = array_map(fn($l) => is_array($l) ? $l['code'] : $l, $b['limits']);
    ok('🔴 the limits are listed: quorum, storage, capacity, placement rules', !array_diff(['quorum', 'storage', 'capacity', 'placement_rules'], $codes), json_encode($codes));
    $assumed = array_values(array_filter($b['limits'], fn($l) => is_array($l) && $l['code'] === 'assumed_available'))[0]['names'] ?? [];
    ok('🔴 ...and that NodeB was ASSUMED available - it has no recorded state', $assumed === ['ZZRG NodeB'], json_encode($assumed));

    // ---- The strict setting: unknown --------------------------------------------------------------
    $conn->exec("INSERT INTO system_settings (setting_key, setting_value) VALUES ('cmdb_impact_unknown_members', 'unknown') ON DUPLICATE KEY UPDATE setting_value = 'unknown'");
    [$g, $b] = $grades('NodeA');
    $is = fn(string $n, string $grade) => ($g[$n]['grade'] ?? null) === $grade;
    ok('treat as unknown: the HA VM is unknown, not "may be interrupted"', $is('VM1', 'unknown'));
    ok('...and so is the cluster', $is('CL', 'unknown'));
    ok('🔴 a node reached only through a missing fact is unknown', $is('App1', 'unknown') && ($g['App1']['unknown_via'] ?? []) === ['ZZRG VM1'], json_encode($g['App1'] ?? null));
    ok('🔴 unknown is never hidden: App2 is affected (via VM2) and still names VM1 as unknown', $is('App2', 'affected') && in_array('ZZRG VM1', $g['App2']['unknown_via'] ?? [], true), json_encode($g['App2'] ?? null));
    $conn->exec("DELETE FROM system_settings WHERE setting_key = 'cmdb_impact_unknown_members'");

    // ---- Storage fails: both nodes go ---------------------------------------------------------------
    [$g, $b] = $grades('Storage');
    $is = fn(string $n, string $grade) => ($g[$n]['grade'] ?? null) === $grade;
    ok('both nodes depend on the storage: both affected', $is('NodeA', 'affected') && $is('NodeB', 'affected'));
    ok('🔴 a cluster that loses every member: affected', $is('CL', 'affected'));
    ok('🔴 an HA VM with nowhere left to run: affected', $is('VM1', 'affected') && $is('App1', 'affected'));

    // ---- No redundancy types: exactly as before ------------------------------------------------------
    $conn->exec("UPDATE cmdb_relationship_types SET redundancy = 'none' WHERE id IN ($member, $placed)");
    [$g, $b] = $grades('NodeA');
    ok('🔴 with no type marked, everything reached is affected, as before', $b['grades_used'] === false && $b['limits'] === []
        && !array_filter($g, fn($n) => $n['grade'] !== 'affected') && count($g) === count(array_filter($g)));
    // The membership edge still spreads impact as an ordinary from_to relationship.
    ok('...and the same objects are reached', isset($g['CL'], $g['VM1'], $g['VM2'], $g['App1'], $g['App2']));
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . $e->getMessage() . ' @ ' . basename($e->getFile()) . ':' . $e->getLine() . "\n";
} finally {
    foreach ($cis as $id) { try { CmdbService::deleteObject($conn, $ctx, $id); } catch (Throwable) {} }
    foreach ($types as $t) { try { $conn->prepare('DELETE FROM cmdb_relationship_types WHERE id = ?')->execute([$t]); } catch (Throwable) {} }
    try {
        if ($setting === false) { $conn->exec("DELETE FROM system_settings WHERE setting_key = 'cmdb_impact_unknown_members'"); }
        else { $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES ('cmdb_impact_unknown_members', ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)")->execute([$setting]); }
    } catch (Throwable) {}
    echo '  cleanup: ' . (int)$conn->query("SELECT COUNT(*) FROM cmdb_objects WHERE name LIKE 'ZZRG %'")->fetchColumn() . ' ZZRG CIs, '
        . (int)$conn->query("SELECT COUNT(*) FROM cmdb_relationship_types WHERE verb LIKE 'ZZRG %'")->fetchColumn() . " ZZRG types left\n";
}
echo str_repeat('-', 70) . "\n  {$pass} passed, {$fail} failed\n";
exit($fail ? 1 : 0);
