<?php
/* 🔴 NEVER OVER THE WEB. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Repeating task evidence (#181) - the record and the report, end to end.
 *
 *   php tests/task-evidence.php [base-url]
 *
 * Builds two throwaway series ('ZZ-EV ...') and proves, each with a positive control:
 *   - a fixed-schedule series: on time; on time after rescheduling (Dr4inder's 1 March ->
 *     8 March -> done 7 March); a due date moved AFTER completion flags but never re-judges;
 *     late; skipped with its reason; removed with its frozen checklist results and exception;
 *     overdue; missing - and the report creates nothing;
 *   - an after-completion series: completing makes exactly one successor, reopening and
 *     completing again does not make a second, and a successor that was expected and never
 *     appeared is MISSING;
 *   - with a base URL: the board's drag (api/tasks/reorder.php) now makes the successor.
 * Deletes everything it made.
 *
 * @wiki Repeating-Task-Evidence-Developer-Guide
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/services/tasks.php';
require_once __DIR__ . '/../includes/task_evidence.php';

$BASE = isset($argv[1]) ? rtrim($argv[1], '/') . '/' : null;
$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$admin = (int)$conn->query("SELECT id FROM analysts WHERE is_active = 1 AND is_admin = 1 ORDER BY id LIMIT 1")->fetchColumn();
$ctx = new ActorContext($admin, null, 'ui', 'en', 'ZZ evidence tester');
$open   = (int)$conn->query("SELECT id FROM task_statuses WHERE is_active=1 AND is_closed=0 ORDER BY is_default DESC, display_order, id LIMIT 1")->fetchColumn();
$closed = (int)$conn->query("SELECT id FROM task_statuses WHERE is_active=1 AND is_closed=1 ORDER BY display_order, id LIMIT 1")->fetchColumn();
$closedName = (string)$conn->query("SELECT name FROM task_statuses WHERE id = {$closed}")->fetchColumn();
$openName   = (string)$conn->query("SELECT name FROM task_statuses WHERE id = {$open}")->fetchColumn();

$rids = []; $tids = [];
$settingsBefore = $conn->query("SELECT setting_key, setting_value FROM system_settings WHERE setting_key LIKE 'tasks_evidence_%'")->fetchAll(PDO::FETCH_KEY_PAIR);

/** A series row, and its first version back-dated to $since. */
function zzSeries(PDO $c, string $mode, string $since, array $extra = []): int {
    global $rids;
    $c->prepare("INSERT INTO task_recurrences (mode, freq, interval_n, month_mode, day_of_month, ends_mode, occurrences_created, next_due_date, is_active, created_by_id, created_datetime, updated_datetime)
                 VALUES (?, ?, 1, ?, ?, 'never', 1, NULL, 1, NULL, UTC_TIMESTAMP(), UTC_TIMESTAMP())")
      ->execute([$mode, $extra['freq'] ?? 'monthly', ($extra['freq'] ?? 'monthly') === 'monthly' ? 'dom' : null, ($extra['freq'] ?? 'monthly') === 'monthly' ? 1 : null]);
    $rid = (int)$c->lastInsertId();
    $rids[] = $rid;
    return $rid;
}
/** A task in a series, made for $scheduled, created at $createdUtc. */
function zzTask(PDO $c, int $rid, string $scheduled, string $createdUtc, string $via = 'repeat_schedule', ?int $prev = null): int {
    global $tids, $open, $admin;
    $c->prepare("INSERT INTO tasks (title, status_id, due_date, scheduled_date, recurrence_id, created_via, previous_occurrence_id, created_by_id, created_datetime, updated_datetime)
                 VALUES ('ZZ-EV occurrence', ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP())")
      ->execute([$open, $scheduled, $scheduled, $rid, $via, $prev, $admin, $createdUtc]);
    $id = (int)$c->lastInsertId();
    $tids[] = $id;
    $c->prepare("INSERT INTO task_events (task_id, recurrence_id, event_type, actor_kind, to_value, detail_json, created_datetime)
                 VALUES (?, ?, 'created', 'system', ?, ?, ?)")->execute([$id, $rid, $scheduled, json_encode(['via' => $via]), $createdUtc]);
    return $id;
}
/** Back-date a completion and the events written with it. */
function zzBackdate(PDO $c, int $taskId, string $completedUtc): void {
    $c->prepare("UPDATE tasks SET completed_datetime = ? WHERE id = ?")->execute([$completedUtc, $taskId]);
    $c->prepare("UPDATE task_events SET created_datetime = ? WHERE task_id = ? AND event_type IN ('status_changed','completed','skipped','successor')")->execute([$completedUtc, $taskId]);
}
$rowFor = function (array $report, ?int $taskId, ?string $scheduled = null) {
    foreach ($report['rows'] as $r) {
        if ($taskId !== null && ($r['task_id'] ?? null) === $taskId) return $r;
        if ($taskId === null && $r['task_id'] === null && $r['scheduled'] === $scheduled) return $r;
    }
    return null;
};

echo "\nRepeating task evidence (#181)\n" . str_repeat('=', 70) . "\n";
try {
    $y = (int)date('Y');
    $d = fn(int $m, int $day) => sprintf('%04d-%02d-%02d', $m > (int)date('n') ? $y - 1 : $y, $m, $day);
    // Twelve months back so every month used below is in the past.
    $m = fn(int $back) => (new DateTimeImmutable('first day of this month'))->modify("-{$back} month");

    echo "\nFixed schedule - outcomes\n";
    $rid = zzSeries($conn, 'schedule', $m(8)->format('Y-m-d'));
    TaskHistory::recordRuleVersion($conn, $rid, 'created', $ctx);
    $conn->prepare("UPDATE task_recurrence_versions SET created_datetime = ? WHERE recurrence_id = ?")->execute([$m(8)->format('Y-m-d 00:00:00'), $rid]);

    $mo = [];
    foreach ([7, 6, 5, 4, 3, 2, 1] as $back) $mo[$back] = $m($back)->format('Y-m-01');

    // 7 months ago: completed on time.
    $t7 = zzTask($conn, $rid, $mo[7], $m(7)->format('Y-m-01 06:00:00'));
    TasksService::saveTask($conn, $ctx, ['id' => $t7, 'status_id' => $closed]);
    zzBackdate($conn, $t7, $m(7)->format('Y-m-01 10:00:00'));

    // 6 months ago: Dr4inder's example - due the 1st, moved to the 8th, done on the 7th.
    $t6 = zzTask($conn, $rid, $mo[6], $m(6)->format('Y-m-01 06:00:00'));
    TasksService::saveTask($conn, $ctx, ['id' => $t6, 'due_date' => $m(6)->format('Y-m-08')]);
    TasksService::saveTask($conn, $ctx, ['id' => $t6, 'status_id' => $closed]);
    zzBackdate($conn, $t6, $m(6)->format('Y-m-07 10:00:00'));
    // ...and then, after completion, somebody moves the due date again.
    TasksService::saveTask($conn, $ctx, ['id' => $t6, 'due_date' => $m(6)->format('Y-m-20')]);

    // 5 months ago: never created at all.

    // 4 months ago: completed late.
    $t4 = zzTask($conn, $rid, $mo[4], $m(4)->format('Y-m-01 06:00:00'));
    TasksService::saveTask($conn, $ctx, ['id' => $t4, 'status_id' => $closed]);
    zzBackdate($conn, $t4, $m(4)->format('Y-m-05 10:00:00'));

    // 3 months ago: skipped, with a reason.
    $t3 = zzTask($conn, $rid, $mo[3], $m(3)->format('Y-m-01 06:00:00'));
    TasksService::skipOccurrence($conn, $ctx, $t3, 'ZZ-EV the server was being replaced');

    // 2 months ago: a failed checklist step, then the occurrence deleted.
    $t2 = zzTask($conn, $rid, $mo[2], $m(2)->format('Y-m-01 06:00:00'));
    $conn->prepare("INSERT INTO task_checklists (task_id, title, closure_mode, sort_order, created_datetime) VALUES (?, 'ZZ-EV restore test', 'warn', 1, UTC_TIMESTAMP())")->execute([$t2]);
    $chk = (int)$conn->lastInsertId();
    $conn->prepare("INSERT INTO task_checklist_items (task_checklist_id, title, is_mandatory, is_completed, result_mode, result, exception_note, completed_by_name, completed_datetime, sort_order)
                    VALUES (?, 'Restore a test file', 1, 1, 'pass_fail', 'fail', 'ZZ-EV checksum mismatch', 'ZZ evidence tester', UTC_TIMESTAMP(), 1)")->execute([$chk]);
    TasksService::deleteTask($conn, $ctx, $t2);

    // 1 month ago: open and past due.
    $t1 = zzTask($conn, $rid, $mo[1], $m(1)->format('Y-m-01 06:00:00'));

    $countBefore = (int)$conn->query("SELECT COUNT(*) FROM tasks WHERE recurrence_id = {$rid}")->fetchColumn();
    $rep = TaskEvidence::report($conn, $rid, $m(8)->format('Y-m-d'), date('Y-m-d'), $admin);
    ok('the report creates nothing (criterion 2)', (int)$conn->query("SELECT COUNT(*) FROM tasks WHERE recurrence_id = {$rid}")->fetchColumn() === $countBefore);

    $r = $rowFor($rep, $t7);
    ok('POSITIVE CONTROL: done on the day it was due is On time', ($r['outcome'] ?? '') === 'on_time', $r['outcome'] ?? 'no row');
    $r = $rowFor($rep, $t6);
    ok('due 1st, moved to the 8th, done the 7th: On time AFTER RESCHEDULING', ($r['outcome'] ?? '') === 'on_time_rescheduled', $r['outcome'] ?? 'no row');
    ok('...flagged Rescheduled', !empty($r['flags']['rescheduled']));
    ok('...the due date in force at completion is kept (the 8th)', ($r['due_at_completion'] ?? '') === $m(6)->format('Y-m-08'), $r['due_at_completion'] ?? '');
    ok('...a due date moved AFTER completion is flagged', !empty($r['flags']['due_changed_after_completion']));
    ok('...and never re-judges it (still not plain On time)', ($r['outcome'] ?? '') !== 'on_time');
    $r = $rowFor($rep, $t4);
    ok('done on the 5th, due the 1st: Late', ($r['outcome'] ?? '') === 'late', $r['outcome'] ?? 'no row');
    $r = $rowFor($rep, $t3);
    ok('skipped is Skipped, with its reason', ($r['outcome'] ?? '') === 'skipped' && ($r['skip_reason'] ?? '') === 'ZZ-EV the server was being replaced', ($r['outcome'] ?? '') . '/' . ($r['skip_reason'] ?? ''));
    $r = $rowFor($rep, $t2);
    ok('a deleted occurrence is Removed', ($r['outcome'] ?? '') === 'removed', $r['outcome'] ?? 'no row');
    ok('...with its checklist result kept', ($r['checklists'][0]['items'][0]['result'] ?? '') === 'fail');
    ok('...and its exception', ($r['exceptions'][0]['note'] ?? '') === 'ZZ-EV checksum mismatch');
    ok('...and who removed it', ($r['removed']['by'] ?? '') === 'ZZ evidence tester');
    $r = $rowFor($rep, $t1);
    ok('open and past its due date is Overdue', ($r['outcome'] ?? '') === 'overdue', $r['outcome'] ?? 'no row');
    $r = $rowFor($rep, null, $mo[5]);
    ok('the month never created is MISSING', ($r['outcome'] ?? '') === 'missing', $r ? $r['outcome'] : 'no row');
    ok('...and the explanation does not claim the job did not run', $r && !str_contains($r['explanation']['code'] ?? '', 'not_run'));
    ok('...it says run history is not available (before recording began)', ($r['explanation']['code'] ?? '') === 'history_not_available', $r['explanation']['code'] ?? '');
    ok('summary counts one of each', $rep['summary']['on_time'] === 1 && $rep['summary']['on_time_rescheduled'] === 1 && $rep['summary']['late'] === 1
        && $rep['summary']['skipped'] === 1 && $rep['summary']['removed'] === 1 && $rep['summary']['missing'] >= 1, json_encode($rep['summary']));

    echo "\nDeleting is a setting\n";
    $conn->exec("INSERT INTO system_settings (setting_key, setting_value) VALUES ('tasks_evidence_delete_occurrence', 'refuse') ON DUPLICATE KEY UPDATE setting_value = 'refuse'");
    $refused = false;
    try { TasksService::deleteTask($conn, $ctx, $t1); } catch (ServiceError $e) { $refused = $e->errorCode === 'occurrence_delete_refused'; }
    ok('set to "not allowed", deleting an occurrence is refused', $refused);
    ok('...and it is still there', (bool)$conn->query("SELECT 1 FROM tasks WHERE id = {$t1}")->fetchColumn());

    echo "\nAfter completion - successors\n";
    $rc = zzSeries($conn, 'completion', $m(3)->format('Y-m-d'), ['freq' => 'weekly']);
    TaskHistory::recordRuleVersion($conn, $rc, 'created', $ctx);
    $c1 = zzTask($conn, $rc, date('Y-m-d', strtotime('-20 days')), gmdate('Y-m-d H:i:s', strtotime('-21 days')), 'manual');
    TasksService::moveTask($conn, $ctx, $c1, ['status_id' => $closed]);
    $succ = $conn->query("SELECT id FROM tasks WHERE previous_occurrence_id = {$c1}")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($succ as $s) $tids[] = (int)$s;
    ok('completing makes ONE successor', count($succ) === 1, (string)count($succ));
    TasksService::saveTask($conn, $ctx, ['id' => $c1, 'status_id' => $open]);
    TasksService::saveTask($conn, $ctx, ['id' => $c1, 'status_id' => $closed]);
    $succ2 = $conn->query("SELECT id FROM tasks WHERE previous_occurrence_id = {$c1}")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($succ2 as $s) $tids[] = (int)$s;
    ok('reopening and completing again does NOT make a second', count($succ2) === 1, (string)count($succ2));
    ok('the successor says the repeat made it', (string)$conn->query("SELECT created_via FROM tasks WHERE id = " . (int)$succ2[0])->fetchColumn() === 'repeat_completion');
    $c2 = (int)$succ2[0];
    $repC = TaskEvidence::report($conn, $rc, date('Y-m-d', strtotime('-60 days')), date('Y-m-d', strtotime('+60 days')), $admin);
    ok('POSITIVE CONTROL: with its successor in place nothing is missing', $repC['summary']['missing'] === 0, json_encode($repC['summary']));
    // The successor that should exist and does not: complete it, then take away what that made.
    TasksService::saveTask($conn, $ctx, ['id' => $c2, 'status_id' => $closed]);
    $c3 = (int)$conn->query("SELECT id FROM tasks WHERE previous_occurrence_id = {$c2}")->fetchColumn();
    ok('completing the successor makes the next one', $c3 > 0);
    $conn->exec("DELETE FROM tasks WHERE id = {$c3}");   // as if it had never been created
    $repC = TaskEvidence::report($conn, $rc, date('Y-m-d', strtotime('-60 days')), date('Y-m-d', strtotime('+60 days')), $admin);
    $miss = array_values(array_filter($repC['rows'], fn($x) => $x['outcome'] === 'missing'));
    ok('a successor that was expected and never appeared is MISSING', count($miss) === 1, json_encode($repC['summary']));
    ok('...naming the completion that made it due', ($miss[0]['missing_successor_of'] ?? 0) === $c2);

    if ($BASE !== null) {
        echo "\nThe board's drag makes the successor (it never did)\n";
        $c4 = zzTask($conn, $rc, date('Y-m-d', strtotime('-2 days')), gmdate('Y-m-d H:i:s', strtotime('-3 days')), 'manual');
        $sess = 'zzev' . bin2hex(random_bytes(4));
        $tok = bin2hex(random_bytes(16));
        $name = 'ZZ evidence tester';
        file_put_contents(session_save_path() . '/sess_' . $sess, 'analyst_id|i:' . $admin . ';analyst_name|s:' . strlen($name) . ':"' . $name
            . '";csrf_token|s:' . strlen($tok) . ':"' . $tok . '";');
        $ch = curl_init($BASE . 'api/tasks/reorder.php');
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_COOKIE => 'PHPSESSID=' . $sess,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json', 'X-CSRF-Token: ' . $tok],
            CURLOPT_POSTFIELDS => json_encode(['task_id' => $c4, 'new_status' => $closedName, 'positions' => [['id' => $c4, 'board_position' => 0]]])]);
        $res = json_decode((string)curl_exec($ch), true);
        @unlink(session_save_path() . '/sess_' . $sess);
        $made = $conn->query("SELECT id FROM tasks WHERE previous_occurrence_id = {$c4}")->fetchAll(PDO::FETCH_COLUMN);
        foreach ($made as $s) $tids[] = (int)$s;
        ok('the drag was accepted', !empty($res['success']), json_encode($res));
        ok('dragging into a closed column made the next occurrence', count($made) === 1, (string)count($made));
        ok('...and recorded who completed it', (int)$conn->query("SELECT completed_by_id FROM tasks WHERE id = {$c4}")->fetchColumn() === $admin);
    }
} finally {
    // Tidy everything away - tasks, their history, the series and their records.
    $conn->exec("DELETE FROM system_settings WHERE setting_key LIKE 'tasks_evidence_%'");
    $ins = $conn->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($settingsBefore as $k => $v) $ins->execute([$k, $v]);
    if ($rids) {
        $in = implode(',', array_map('intval', $rids));
        foreach ($conn->query("SELECT id FROM tasks WHERE recurrence_id IN ($in)")->fetchAll(PDO::FETCH_COLUMN) as $id) $tids[] = (int)$id;
    }
    $tids = array_values(array_unique(array_filter($tids)));
    if ($tids) {
        $tin = implode(',', $tids);
        $conn->exec("DELETE i FROM task_checklist_items i JOIN task_checklists c ON c.id = i.task_checklist_id WHERE c.task_id IN ($tin)");
        $conn->exec("DELETE FROM task_checklists WHERE task_id IN ($tin)");
        $conn->exec("DELETE FROM task_checklist_item_events WHERE task_id IN ($tin)");
        $conn->exec("DELETE FROM task_audit WHERE task_id IN ($tin)");
        $conn->exec("UPDATE tasks SET previous_occurrence_id = NULL, recurrence_master_id = NULL WHERE id IN ($tin)");
        $conn->exec("DELETE FROM tasks WHERE parent_task_id IN ($tin)");
        $conn->exec("DELETE FROM tasks WHERE id IN ($tin)");
        $conn->exec("DELETE FROM task_events WHERE task_id IN ($tin)");
    }
    if ($rids) {
        $in = implode(',', array_map('intval', $rids));
        $conn->exec("DELETE FROM task_events WHERE recurrence_id IN ($in)");
        $conn->exec("DELETE FROM task_removed_records WHERE recurrence_id IN ($in)");
        $conn->exec("DELETE FROM task_recurrence_versions WHERE recurrence_id IN ($in)");
        $conn->exec("DELETE FROM task_recurrences WHERE id IN ($in)");
    }
}

echo "\n$pass passed, $fail failed\n";
exit($fail ? 1 : 0);
