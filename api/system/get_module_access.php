<?php
/**
 * API: module-access summary (issue #30). Admins only.
 * Returns the module registry, the current permission mode, and every team and
 * analyst with their module grants (all-access flag + specific keys) — enough for
 * the System → Modules summary + edit screen to render and edit without more calls.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';

header('Content-Type: application/json');

try {
    $conn = connectToDatabase();

    // Module registry (key => name), as a stable ordered list.
    $modules = [];
    foreach (getModuleRegistry() as $key => $name) {
        $modules[] = ['key' => $key, 'name' => $name, 'read_only' => in_array($key, MODULES_WITH_READ_ONLY, true)];
    }
    // The Read only grants (#180), entity => [module_key => 'read'].
    $readBy = function (string $table, string $idCol) use ($conn): array {
        $map = [];
        try {
            foreach ($conn->query("SELECT $idCol AS eid, module_key FROM $table WHERE access_level = 'read'") as $r) {
                $map[(int) $r['eid']][$r['module_key']] = 'read';
            }
        } catch (Throwable $e) { /* access_level not added yet */ }
        return $map;
    };
    $analystRead = $readBy('analyst_modules', 'analyst_id');
    $teamRead    = $readBy('team_modules', 'team_id');

    // Helper to group "entity_id => [module_key,…]" from a grant table.
    $grantsBy = function (string $table, string $idCol) use ($conn): array {
        $map = [];
        foreach ($conn->query("SELECT $idCol AS eid, module_key FROM $table") as $r) {
            $map[(int) $r['eid']][] = $r['module_key'];
        }
        return $map;
    };
    $analystGrants = $grantsBy('analyst_modules', 'analyst_id');
    $teamGrants    = $grantsBy('team_modules', 'team_id');

    $teams = [];
    foreach ($conn->query("SELECT id, name, can_access_all_modules FROM teams WHERE is_active = 1 ORDER BY name") as $t) {
        $id = (int) $t['id'];
        $teams[] = [
            'id'          => $id,
            'name'        => $t['name'],
            'all_modules' => (int) $t['can_access_all_modules'] === 1,
            'modules'     => $teamGrants[$id] ?? [],
            'levels'      => (object) ($teamRead[$id] ?? []),
        ];
    }

    $analysts = [];
    foreach ($conn->query("SELECT id, full_name, username, can_access_all_modules FROM analysts WHERE is_active = 1 ORDER BY full_name") as $a) {
        $id = (int) $a['id'];
        $analysts[] = [
            'id'          => $id,
            'name'        => $a['full_name'],
            'username'    => $a['username'],
            'all_modules' => (int) $a['can_access_all_modules'] === 1,
            'modules'     => $analystGrants[$id] ?? [],
            'levels'      => (object) ($analystRead[$id] ?? []),
        ];
    }

    echo json_encode([
        'success'  => true,
        'mode'     => getModulePermissionMode($conn),
        'modules'  => $modules,
        // Availability (GH #217): the switched-off modules, and the ones that never can be.
        'disabled'  => disabledModules($conn),
        'always_on' => MODULES_ALWAYS_ON,
        // The Tickets features with their own switch (slice 2).
        'features'  => array_map(fn($k, $n) => ['key' => $k, 'name' => $n], array_keys(TICKET_FEATURES), TICKET_FEATURES),
        'teams'    => $teams,
        'analysts' => $analysts,
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
