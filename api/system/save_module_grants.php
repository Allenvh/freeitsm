<?php
/**
 * API: save who specifically grants ONE module (issue #30). Admins only.
 * POST { module_key: string, team_ids: int[], analyst_ids: int[] }
 *
 * Reconciles the *specific* grants for this module — the team_modules / analyst_modules
 * rows. All-access entities (can_access_all_modules = 1) are managed by their own flag
 * (on the Analysts / Teams screens), not here, so they carry no rows and are ignored.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/admin_api_guard.php'; // System admins only (issue #34)
require_once '../../includes/functions.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');

$input      = json_decode(file_get_contents('php://input'), true);
$moduleKey  = trim($input['module_key'] ?? '');
$teamIds    = array_values(array_unique(array_map('intval', $input['team_ids'] ?? [])));
$analystIds = array_values(array_unique(array_map('intval', $input['analyst_ids'] ?? [])));

if (!array_key_exists($moduleKey, getModuleRegistry())) {
    echo json_encode(['success' => false, 'error' => 'Unknown module']);
    exit;
}

try {
    $conn = connectToDatabase();
    $conn->beginTransaction();

    // The level of each grant (#180): 'read' only on a module that offers Read only, and
    // only when the request names it. A request that says nothing about levels (an older
    // page) keeps whatever each grant had.
    $readOnlyOk = in_array($moduleKey, MODULES_WITH_READ_ONLY, true);
    $sent = $input['levels'] ?? null;
    $old  = ['team' => [], 'analyst' => []];
    foreach (['team' => 'team_modules', 'analyst' => 'analyst_modules'] as $kind => $table) {
        $col = $kind === 'team' ? 'team_id' : 'analyst_id';
        $s = $conn->prepare("SELECT {$col}, access_level FROM {$table} WHERE module_key = ?");
        $s->execute([$moduleKey]);
        $old[$kind] = $s->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
    }
    $levelFor = function (string $kind, int $id) use ($readOnlyOk, $sent, $old): string {
        if (!$readOnlyOk) return 'full';
        $v = is_array($sent) ? ($sent[$kind][$id] ?? $sent[$kind][(string)$id] ?? 'full') : ($old[$kind][$id] ?? 'full');
        return $v === 'read' ? 'read' : 'full';
    };

    // Teams: replace this module's specific grants with exactly the listed teams
    // (skipping any that are all-access — they don't use rows).
    $conn->prepare("DELETE FROM team_modules WHERE module_key = ?")->execute([$moduleKey]);
    if ($teamIds) {
        $ins = $conn->prepare("INSERT INTO team_modules (team_id, module_key, access_level) VALUES (?, ?, ?)");
        $isAll = $conn->prepare("SELECT can_access_all_modules FROM teams WHERE id = ?");
        foreach ($teamIds as $tid) {
            if ($tid <= 0) continue;
            $isAll->execute([$tid]);
            if ((int) $isAll->fetchColumn() === 1) continue; // all-access → managed by flag
            $ins->execute([$tid, $moduleKey, $levelFor('team', $tid)]);
        }
    }

    // Analysts: same.
    $conn->prepare("DELETE FROM analyst_modules WHERE module_key = ?")->execute([$moduleKey]);
    if ($analystIds) {
        $ins = $conn->prepare("INSERT INTO analyst_modules (analyst_id, module_key, access_level) VALUES (?, ?, ?)");
        $isAll = $conn->prepare("SELECT can_access_all_modules FROM analysts WHERE id = ?");
        foreach ($analystIds as $aid) {
            if ($aid <= 0) continue;
            $isAll->execute([$aid]);
            if ((int) $isAll->fetchColumn() === 1) continue;
            $ins->execute([$aid, $moduleKey, $levelFor('analyst', $aid)]);
        }
    }

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
