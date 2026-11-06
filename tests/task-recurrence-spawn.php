<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Recurring tasks: making the next occurrence (#94).
 *
 * The date engine is tested separately and without a database. This is the
 * other half — that completing a task actually produces the next one, that it
 * carries across what the rule says to carry and nothing it should not, and
 * that it fires from BOTH ways a task can be completed.
 *
 * That last one is the point. TasksService::moveTask (dragging a card into a
 * closed column) is documented "No workflow event" and dispatches nothing, so a
 * hook placed only on saveTask would give a task that repeats when you tick it
 * and silently does not when you drag it — and dragging is the commoner action.
 *
 * ⚠️ Touches the database. Everything it makes is prefixed ZZREC and removed in
 * the cleanup at the bottom, including on failure.
 *
 * Run:  php tests/task-recurrence-spawn.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/tasks.php';
require_once __DIR__ . '/../includes/services/task_recurrence.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nRecurring tasks — spawning (#94)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$openStatus   = (int)$conn->query("SELECT id FROM task_statuses WHERE is_active=1 AND is_closed=0 ORDER BY is_default DESC, display_order, id LIMIT 1")->fetchColumn();
$closedStatus = (int)$conn->query("SELECT id FROM task_statuses WHERE is_active=1 AND is_closed=1 ORDER BY display_order, id LIMIT 1")->fetchColumn();
$closedName   = (string)$conn->query("SELECT name FROM task_statuses WHERE id=" . $closedStatus)->fetchColumn();

$made = [];   // every task id created, for cleanup
$rules = [];

/** Create a rule plus its first task. Returns [recurrenceId, taskId]. */
function series(PDO $conn, array $rule, array $task, int $openStatus): array {
    global $made, $rules;
    $r = TaskRecurrence::blankRule();
    $cols = ['mode','freq','interval_n','weekdays','month_mode','day_of_month','nth','nth_weekday',
             'month_of_year','ends_mode','ends_on','max_occurrences'];
    $vals = [];
    foreach ($cols as $c) $vals[] = $rule[$c] ?? $r[$c] ?? null;
    $flags = ['copy_description'=>1,'copy_subtasks'=>1,'copy_assignee'=>1,'copy_tags'=>1,'copy_links'=>0,'copy_attachments'=>0];
    foreach ($flags as $k => $v) $flags[$k] = $rule[$k] ?? $v;

    $conn->prepare("INSERT INTO task_recurrences (" . implode(',', $cols) . ",copy_description,copy_subtasks,copy_assignee,copy_tags,copy_links,copy_attachments,next_due_date,occurrences_created)
                    VALUES (" . rtrim(str_repeat('?,', count($cols)), ',') . ",?,?,?,?,?,?,?,1)")
         ->execute(array_merge($vals, array_values($flags), [$rule['next_due_date'] ?? null]));
    $rid = (int)$conn->lastInsertId();
    $rules[] = $rid;

    $conn->prepare("INSERT INTO tasks (title, description, status_id, due_date, start_date, assigned_analyst_id, recurrence_id, created_datetime, updated_datetime)
                    VALUES (?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())")
         ->execute([$task['title'], $task['description'] ?? null, $openStatus,
                    $task['due_date'] ?? null, $task['start_date'] ?? null,
                    $task['assigned_analyst_id'] ?? null, $rid]);
    $tid = (int)$conn->lastInsertId();
    $conn->prepare("UPDATE tasks SET recurrence_master_id = id WHERE id = ?")->execute([$tid]);
    $made[] = $tid;
    return [$rid, $tid];
}

function taskRow(PDO $conn, int $id): ?array {
    $s = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
    $s->execute([$id]);
    return $s->fetch(PDO::FETCH_ASSOC) ?: null;
}

try {
    $ctx = ActorContext::fromSession($conn);

    // ---- 1. Completing via saveTask spawns the next -----------------------
    echo "\nCompleting a task by saving a status change:\n";
    [$rid, $t1] = series($conn, ['mode'=>'completion','freq'=>'daily','interval_n'=>7],
        ['title'=>'ZZREC weekly backup check', 'description'=>'ZZREC check the backups'], $openStatus);

    TasksService::saveTask($conn, $ctx, ['id'=>$t1, 'status'=>$closedName]);
    $spawned = $conn->prepare("SELECT id FROM tasks WHERE recurrence_id = ? AND id <> ? ORDER BY id DESC LIMIT 1");
    $spawned->execute([$rid, $t1]);
    $t2 = (int)$spawned->fetchColumn();
    if ($t2) $made[] = $t2;
    ok('a next occurrence is created', $t2 > 0, 'nothing was spawned');

    if ($t2) {
        $n = taskRow($conn, $t2);
        ok('it is due 7 days from today', ($n['due_date'] ?? '') === gmdate('Y-m-d', strtotime('+7 day')),
            'due ' . ($n['due_date'] ?? 'null'));
        ok('it starts OPEN, not in the status the last one closed into', (int)$n['status_id'] === $openStatus);
        ok('it carries no completion timestamp', empty($n['completed_datetime']));
        ok('it belongs to the same series', (int)$n['recurrence_id'] === $rid);
        ok('it points back at the first task as master', (int)$n['recurrence_master_id'] === $t1);
        ok('the description carried', trim((string)$n['description']) === 'ZZREC check the backups');
    }

    // ---- 2. Completing by DRAGGING must spawn too --------------------------
    echo "\nCompleting a task by dragging it into a closed column:\n";
    [$rid2, $t3] = series($conn, ['mode'=>'completion','freq'=>'daily','interval_n'=>1],
        ['title'=>'ZZREC daily standup note'], $openStatus);
    TasksService::moveTask($conn, $ctx, $t3, ['status'=>$closedName]);
    $s2 = $conn->prepare("SELECT id FROM tasks WHERE recurrence_id = ? AND id <> ? ORDER BY id DESC LIMIT 1");
    $s2->execute([$rid2, $t3]);
    $t4 = (int)$s2->fetchColumn();
    if ($t4) $made[] = $t4;
    ok('dragging to Done also spawns the next one', $t4 > 0,
       'moveTask fires no workflow event, so this needs its own hook');

    // ---- 3. The copy flags are honoured ------------------------------------
    echo "\nThe copy flags:\n";
    [$rid3, $t5] = series($conn,
        ['mode'=>'completion','freq'=>'daily','interval_n'=>1,'copy_description'=>0,'copy_assignee'=>0],
        ['title'=>'ZZREC audit', 'description'=>'ZZREC secret notes', 'assigned_analyst_id'=>1], $openStatus);
    TasksService::saveTask($conn, $ctx, ['id'=>$t5, 'status'=>$closedName]);
    $s3 = $conn->prepare("SELECT id FROM tasks WHERE recurrence_id = ? AND id <> ? ORDER BY id DESC LIMIT 1");
    $s3->execute([$rid3, $t5]);
    $t6 = (int)$s3->fetchColumn();
    if ($t6) $made[] = $t6;
    if ($t6) {
        $n = taskRow($conn, $t6);
        ok('copy_description = 0 leaves the description empty', empty($n['description']),
            'got: ' . var_export($n['description'], true));
        ok('copy_assignee = 0 leaves it unassigned', empty($n['assigned_analyst_id']));
        ok('the title always carries', $n['title'] === 'ZZREC audit');
    }

    // ---- 4. A series that has run out stops --------------------------------
    echo "\nA series that has reached its limit:\n";
    [$rid4, $t7] = series($conn,
        ['mode'=>'completion','freq'=>'daily','interval_n'=>1,'ends_mode'=>'after_count','max_occurrences'=>1],
        ['title'=>'ZZREC one and done'], $openStatus);
    TasksService::saveTask($conn, $ctx, ['id'=>$t7, 'status'=>$closedName]);
    $c4 = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE recurrence_id = ? AND id <> ?");
    $c4->execute([$rid4, $t7]);
    ok('no further occurrence after the last one', (int)$c4->fetchColumn() === 0);
    $active = $conn->prepare("SELECT is_active FROM task_recurrences WHERE id = ?");
    $active->execute([$rid4]);
    ok('and the series is switched off', (int)$active->fetchColumn() === 0);

    // ---- 5. Fixed schedule catches up --------------------------------------
    echo "\nA fixed schedule that has not run for a while:\n";
    $threeDaysAgo = gmdate('Y-m-d', strtotime('-3 day'));
    [$rid5, $t8] = series($conn,
        ['mode'=>'schedule','freq'=>'daily','interval_n'=>1,'next_due_date'=>$threeDaysAgo],
        ['title'=>'ZZREC nightly report', 'due_date'=>$threeDaysAgo], $openStatus);
    // Scoped to this test's own series. Unscoped, this acts on every series in
    // the database — which on a developer machine means the developer's real
    // tasks, and makes the count below somebody else's to influence.
    $newIds = TaskRecurrence::runDue($conn, null, 24, [$rid5]);
    foreach ($newIds as $id) $made[] = $id;
    $c5 = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE recurrence_id = ? AND id <> ?");
    $c5->execute([$rid5, $t8]);
    $n5 = (int)$c5->fetchColumn();
    ok('it catches up rather than skipping', $n5 >= 3, "made {$n5}, expected at least 3");
    ok('and it does not run away', $n5 <= 24, "made {$n5} — the per-run cap failed");

    // ---- 5b. An occurrence arrives when work can START ---------------------
    //
    // The defect this covers: every test above uses a task with a due date and
    // no start date, so a worker that fired on the due date passed all of them.
    // A task that needs a fortnight, created on the day it falls due, is born a
    // fortnight past the day it should have been started — a two-week bar
    // lying entirely in the past that nobody could ever have done.
    echo "\nA task with a duration, due in the future:\n";
    $due10 = gmdate('Y-m-d', strtotime('+10 day'));
    [$rid6, $t9] = series($conn,
        ['mode'=>'schedule','freq'=>'weekly','interval_n'=>1,'next_due_date'=>$due10],
        ['title'=>'ZZREC fortnightly audit',
         'due_date'   => gmdate('Y-m-d'),
         'start_date' => gmdate('Y-m-d', strtotime('-14 day'))], $openStatus);

    foreach (TaskRecurrence::runDue($conn, null, 24, [$rid6]) as $id) $made[] = $id;
    $g = $conn->prepare("SELECT * FROM tasks WHERE recurrence_id = ? AND id <> ? ORDER BY id");
    $g->execute([$rid6, $t9]);
    $spawn6 = $g->fetchAll(PDO::FETCH_ASSOC);

    ok('exactly one occurrence is created', count($spawn6) === 1, 'made ' . count($spawn6));
    if ($spawn6) {
        ok('it exists BEFORE its due date',
           (string)$spawn6[0]['due_date'] === $due10 && $due10 > gmdate('Y-m-d'),
           "due {$spawn6[0]['due_date']}, today " . gmdate('Y-m-d'));
        ok('and its fortnight is intact, starting in the past',
           (string)$spawn6[0]['start_date'] === gmdate('Y-m-d', strtotime('-4 day')),
           "start {$spawn6[0]['start_date']}, expected " . gmdate('Y-m-d', strtotime('-4 day')));
    } else {
        ok('it exists BEFORE its due date', false, 'nothing was created');
        ok('and its fortnight is intact, starting in the past', false, 'nothing was created');
    }

    // Negative controls. Without these the test above would also pass a worker
    // that simply created everything, which is the behaviour we rejected.
    echo "\nNegative controls — what must still NOT be created:\n";
    [$rid7, $t10] = series($conn,
        ['mode'=>'schedule','freq'=>'weekly','interval_n'=>1,'next_due_date'=>$due10],
        ['title'=>'ZZREC point in time', 'due_date'=>gmdate('Y-m-d')], $openStatus);

    [$rid8, $t11] = series($conn,
        ['mode'=>'schedule','freq'=>'weekly','interval_n'=>1,'next_due_date'=>$due10],
        ['title'=>'ZZREC two day job',
         'due_date'   => gmdate('Y-m-d'),
         'start_date' => gmdate('Y-m-d', strtotime('-2 day'))], $openStatus);

    foreach (TaskRecurrence::runDue($conn, null, 24, [$rid7, $rid8]) as $id) $made[] = $id;
    $c7 = $conn->prepare("SELECT COUNT(*) FROM tasks WHERE recurrence_id = ? AND id <> ?");
    $c7->execute([$rid7, $t10]);
    ok('a task with no start date still waits for its due date', (int)$c7->fetchColumn() === 0);
    $c7->execute([$rid8, $t11]);
    ok('a two-day job due in ten days is not created yet', (int)$c7->fetchColumn() === 0);

    // ---- 6. A task with no rule is untouched -------------------------------
    echo "\nNegative control:\n";
    $conn->prepare("INSERT INTO tasks (title, status_id, created_datetime, updated_datetime) VALUES ('ZZREC ordinary task', ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())")
         ->execute([$openStatus]);
    $plain = (int)$conn->lastInsertId(); $made[] = $plain;
    $before = (int)$conn->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
    TasksService::saveTask($conn, $ctx, ['id'=>$plain, 'status'=>$closedName]);
    $after = (int)$conn->query("SELECT COUNT(*) FROM tasks")->fetchColumn();
    ok('completing a task that does not repeat creates nothing', $before === $after,
       "task count went {$before} -> {$after}");

} finally {
    // ---- Cleanup -----------------------------------------------------------
    try {
        // #181's history outlives its tasks on purpose (no foreign keys), so a test
        // has to remove what it wrote there itself, BEFORE the tasks go.
        $conn->exec("DELETE e FROM task_events e JOIN tasks t ON t.id = e.task_id WHERE t.title LIKE 'ZZREC%'");
        // TRAP: "ZZREC audit" is assigned to analyst 1, so its status changes ring that
        // person's bell. A test must take its notifications away with its tasks.
        $conn->exec("DELETE n FROM notifications n JOIN tasks t ON t.id = n.entity_id WHERE n.entity_type = 'task' AND t.title LIKE 'ZZREC%'");
        $conn->prepare("DELETE FROM tasks WHERE title LIKE 'ZZREC%'")->execute();
        if ($rules) {
            $ph = implode(',', array_fill(0, count($rules), '?'));
            $conn->prepare("DELETE FROM task_events WHERE recurrence_id IN ($ph)")->execute($rules);
            $conn->prepare("DELETE FROM task_recurrence_versions WHERE recurrence_id IN ($ph)")->execute($rules);
            $conn->prepare("DELETE FROM task_recurrences WHERE id IN ($ph)")->execute($rules);
        }
        $left = (int)$conn->query("SELECT COUNT(*) FROM tasks WHERE title LIKE 'ZZREC%'")->fetchColumn();
        echo "\n  cleanup: " . ($left === 0 ? "no ZZREC rows left\n" : "⚠️  {$left} ZZREC rows REMAIN\n");
    } catch (Exception $e) {
        echo "\n  ⚠️  cleanup failed: " . $e->getMessage() . "\n";
    }
}

echo str_repeat('=', 70) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);
