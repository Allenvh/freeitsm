<?php
/* 🔴 NEVER OVER THE WEB - see tests/README.md. This one writes to the real
   tables: a throwaway project, two stages and three tasks, all named ZZPO and
   removed in the cleanup at the bottom, including on failure. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Projects: the order of the tasks within a stage (GH #184).
 *
 *   - a plan nobody has reordered keeps its old order (board_position);
 *   - ProjectsService::orderTasks() puts a lane in the order given, and only
 *     touches that project's tasks in that lane;
 *   - moving a task to another stage clears its number, so it lands at the end
 *     instead of somewhere in the middle carrying the old lane's number;
 *   - someone who may not change the project is refused.
 *
 * Needs tasks.project_position (Database Verification). Without it, it checks
 * that ordering is refused politely and the plan still reads, then stops.
 *
 * The tasks are INSERTed directly, not through TasksService, so no workflow,
 * notification or email fires on the install this runs against.
 *
 * Run:  php tests/project-plan-order.php
 */

chdir(__DIR__ . '/..');
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/services/projects.php';
require_once 'includes/projects/read.php';

$pass = 0; $fail = 0;
function is_(string $what, $got, $want): void {
    global $pass, $fail;
    if ($got === $want) { $pass++; printf("  PASS  %-60s\n", $what); }
    else { $fail++; printf("  FAIL  %-60s got %s, wanted %s\n", $what, json_encode($got), json_encode($want)); }
}

$conn = connectToDatabase();
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_admin = 1 AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
$_SESSION = ['analyst_id' => $admin, 'analyst_name' => 'ZZPO test', 'is_admin' => 1];
$ctx = ActorContext::fromSession($conn);
$ready = projectPlanOrderReady($conn);

echo "\nProjects: task order within a stage (#184)\n" . str_repeat('=', 78) . "\n";
echo "  tasks.project_position " . ($ready ? "exists" : "NOT YET - run Database Verification for the full test") . "\n\n";

$projectId = null; $taskIds = [];
try {
    $projectId = ProjectsService::createProject($conn, $ctx, ['name' => 'ZZPO plan order test'], null);
    $s1 = ProjectsService::saveStage($conn, $ctx, $projectId, ['name' => 'ZZPO stage one']);
    $s2 = ProjectsService::saveStage($conn, $ctx, $projectId, ['name' => 'ZZPO stage two']);
    $status = (int)$conn->query("SELECT id FROM task_statuses WHERE is_closed = 0 ORDER BY display_order, id LIMIT 1")->fetchColumn();
    $tenant = $conn->query("SELECT tenant_id FROM projects WHERE id = " . (int)$projectId)->fetchColumn();
    $ins = $conn->prepare("INSERT INTO tasks (title, status_id, created_by_id, created_datetime, project_id, project_stage_id, board_position, tenant_id)
                           VALUES (?, ?, ?, UTC_TIMESTAMP(), ?, ?, ?, ?)");
    // board_position 3, 1, 2: the order a plan used before #184 is therefore B, C, A.
    foreach ([['ZZPO A', 3], ['ZZPO B', 1], ['ZZPO C', 2]] as [$title, $bp]) {
        $ins->execute([$title, $status, $admin, $projectId, $s1, $bp, $tenant === false ? null : $tenant]);
        $taskIds[$title] = (int)$conn->lastInsertId();
    }
    [$A, $B, $C] = [$taskIds['ZZPO A'], $taskIds['ZZPO B'], $taskIds['ZZPO C']];

    /** The plan's task order in a stage, as the Plan tab reads it. */
    $lane = function ($stageId) use ($conn, $projectId) {
        $row = ProjectsService::loadRow($conn, $projectId);
        $plan = projectDetail($conn, $row);
        $out = [];
        foreach ($plan['tasks'] as $t) if ((int)$t['project_stage_id'] === (int)$stageId) $out[] = (int)$t['id'];
        return $out;
    };

    is_('untouched plan keeps its old order (board_position)', $lane($s1), [$B, $C, $A]);

    if (!$ready) {
        $refused = false;
        try { ProjectsService::orderTasks($conn, $ctx, $projectId, $s1, [$A, $B, $C]); } catch (ServiceError $e) { $refused = $e->errorCode === 'not_ready'; }
        is_('before Database Verification, ordering is refused politely', $refused, true);
        ProjectsService::assignTask($conn, $ctx, $A, $projectId, $s2);
        is_('and moving a task between stages still works', $lane($s2), [$A]);
    } else {
        ProjectsService::orderTasks($conn, $ctx, $projectId, $s1, [$A, $B, $C]);
        is_('orderTasks puts the lane in the order given', $lane($s1), [$A, $B, $C]);
        ProjectsService::orderTasks($conn, $ctx, $projectId, $s1, [$C, $A]);
        is_('a task missing from the list goes after the numbered ones', $lane($s1), [$C, $A, $B]);

        // An id from another lane changes nothing.
        ProjectsService::orderTasks($conn, $ctx, $projectId, $s2, [$A]);
        is_('an id from another lane is ignored', (int)$conn->query("SELECT project_position FROM tasks WHERE id = $A")->fetchColumn(), 2);

        ProjectsService::assignTask($conn, $ctx, $C, $projectId, $s2);
        is_('moving to another stage clears the number', $conn->query("SELECT project_position FROM tasks WHERE id = $C")->fetchColumn(), null);
        ProjectsService::orderTasks($conn, $ctx, $projectId, $s2, [$C]);   // stage two is now a numbered lane
        ProjectsService::assignTask($conn, $ctx, $B, $projectId, $s2);
        is_('a task arriving in a numbered lane lands at its end', $lane($s2), [$C, $B]);
        ProjectsService::orderTasks($conn, $ctx, $projectId, $s2, [$B, $C]);
        is_('and can then be put first', $lane($s2), [$B, $C]);

        // Someone who may not change the project.
        $other = (int)$conn->query("SELECT id FROM analysts WHERE is_admin = 0 AND is_active = 1 ORDER BY id LIMIT 1")->fetchColumn();
        if ($other) {
            $_SESSION = ['analyst_id' => $other, 'analyst_name' => 'ZZPO other', 'is_admin' => 0, 'allowed_modules' => ['projects']];
            $ctxOther = ActorContext::fromSession($conn);
            $refused = false;
            try { ProjectsService::orderTasks($conn, $ctxOther, $projectId, $s2, [$C, $B]); } catch (ServiceError $e) { $refused = in_array($e->kind, ['forbidden', 'not_found'], true); }
            is_('someone outside the project team is refused', $refused, true);
            is_('and the order is unchanged', $lane($s2), [$B, $C]);
            $_SESSION = ['analyst_id' => $admin, 'analyst_name' => 'ZZPO test', 'is_admin' => 1];
        }
    }
} catch (Throwable $e) {
    $fail++;
    echo "  FAIL  unexpected: " . $e->getMessage() . "\n";
} finally {
    // Cleanup, whatever happened: the tasks, then the project (which also removes its stages).
    if ($taskIds) $conn->exec("DELETE FROM tasks WHERE id IN (" . implode(',', array_map('intval', $taskIds)) . ")");
    if ($projectId) {
        try { ProjectsService::deleteProject($conn, $ctx, $projectId); } catch (Throwable $e) { echo "  cleanup: " . $e->getMessage() . "\n"; }
    }
    $left = (int)$conn->query("SELECT (SELECT COUNT(*) FROM tasks WHERE title LIKE 'ZZPO %') + (SELECT COUNT(*) FROM projects WHERE name LIKE 'ZZPO %') + (SELECT COUNT(*) FROM project_stages WHERE name LIKE 'ZZPO %')")->fetchColumn();
    is_('cleanup: nothing named ZZPO is left', $left, 0);
}

echo "\n" . str_repeat('-', 78) . "\n  $pass passed, $fail failed\n\n";
exit($fail ? 1 : 0);
