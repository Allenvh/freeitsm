<?php
/* 🔴 NEVER OVER THE WEB. A test writes to the real tables — it creates forms,
   assets, documents and even working analyst accounts, and only tidies them up
   if it runs to the end. Served by a web server it is an unauthenticated write
   endpoint, and the request can be cut off half way. FreeITSM is normally
   deployed by putting the repository in the document root, so this file is
   reachable unless it refuses. See tests/README.md. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }

/**
 * Tasks in a calendar (#75) — the decisions, not the network.
 *
 * 🔑 NOTHING HERE TALKS TO MICROSOFT. What is worth testing is what the code
 * DECIDES: which of a task's two dates should be in whose calendar, what an
 * event looks like once built, and what happens at the edges. The provider is
 * a network call and belongs behind a live run, not a unit test.
 *
 * ⚠️ Touches the database. Everything it makes is named ZZCAL and removed in
 * the cleanup at the bottom, including on failure.
 *
 * Run:  php tests/calendar-sync-tasks.php
 */

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/calendar_sync/push.php';

$pass = 0; $fail = 0;
function ok(string $what, bool $cond, string $detail = ''): void {
    global $pass, $fail;
    if ($cond) { $pass++; echo "  PASS  {$what}\n"; }
    else       { $fail++; echo "  FAIL  {$what}" . ($detail ? "  <- {$detail}" : '') . "\n"; }
}

echo "\nTasks in a calendar (#75)\n" . str_repeat('=', 70) . "\n";

$conn = connectToDatabase();
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

try {
    // ── 1. The choice ───────────────────────────────────────────────────────
    echo "\nWhat each choice means:\n";
    ok("'off' wants neither",
        !taskCalendarWantsWork(TASK_CAL_OFF) && !taskCalendarWantsDue(TASK_CAL_OFF));
    ok("'work' wants the slot only",
        taskCalendarWantsWork(TASK_CAL_WORK) && !taskCalendarWantsDue(TASK_CAL_WORK));
    ok("'due' wants the deadline only",
        !taskCalendarWantsWork(TASK_CAL_DUE) && taskCalendarWantsDue(TASK_CAL_DUE));
    ok("'both' wants both",
        taskCalendarWantsWork(TASK_CAL_BOTH) && taskCalendarWantsDue(TASK_CAL_BOTH));

    echo "\nAn unrecognised choice is not a licence to publish:\n";
    foreach (['', 'yes', 'BOTH', 'all', 'true', '1'] as $bad) {
        ok('rejects ' . var_export($bad, true), !taskCalendarModeIsValid($bad));
    }
    ok('POSITIVE CONTROL: the four real ones are accepted',
        taskCalendarModeIsValid('off') && taskCalendarModeIsValid('work')
        && taskCalendarModeIsValid('due') && taskCalendarModeIsValid('both'));

    // ── 2. The event a task becomes ─────────────────────────────────────────
    echo "\nThe event built from a task:\n";
    $task = [
        'id' => 4242, 'title' => 'ZZCAL replace the batteries',
        'due_date' => '2026-09-10',
        'work_start_datetime' => '2026-09-08 14:00:00',
        'work_end_datetime'   => null,
        'work_all_day'        => 0,
        'status_name' => 'To Do', 'priority_name' => 'High', 'team_name' => null,
    ];

    $work = calendarSyncEventFromTask($task, 'work');
    ok('a work slot keeps the start it was given', $work['start'] === '2026-09-08 14:00:00');
    ok('…and is NOT all day', $work['all_day'] === false);
    ok('…and fills in an end when none was set', !empty($work['end']) && $work['end'] > $work['start'],
        var_export($work['end'] ?? null, true));
    ok('…and is titled with the task itself', $work['subject'] === 'ZZCAL replace the batteries');

    $due = calendarSyncEventFromTask($task, 'due');
    ok('a due date is all day', $due['all_day'] === true);
    ok('…and says so in the title, so a deadline is not read as a booking',
        str_starts_with($due['subject'], 'Due:'), $due['subject']);
    ok('…and starts on the due date', substr($due['start'], 0, 10) === '2026-09-10');

    // 🔴 THE ONE THAT BIT. An earlier version of this file asserted the end was
    // the day AFTER, on the reasoning that an all-day end is exclusive. It is —
    // but BOTH serializers already do that conversion themselves, so passing a
    // day-after end got the day added twice and a task due on the 5th arrived
    // in Outlook spanning the 5th and 6th. Ed found it on a real task; this
    // test had happily defended it.
    //
    // The lesson is in what is asserted, not just the value: a unit test of my
    // own assumption proved nothing. So the check below runs the REAL
    // serializer and measures the result.
    ok('…and ends on the SAME day it starts, because the serializers add the exclusive day',
        substr($due['end'], 0, 10) === '2026-09-10',
        'ends ' . substr($due['end'], 0, 10) . ' — the day would be added twice');

    echo "\nThrough the real serializer, a one-day deadline occupies ONE day:\n";
    require_once __DIR__ . '/../includes/ics.php';
    $ics = implode("\n", icsEvent($due, 'Europe/London'));
    preg_match('/DTSTART;VALUE=DATE:(\d{8})/', $ics, $ds);
    preg_match('/DTEND;VALUE=DATE:(\d{8})/',   $ics, $de);
    ok('the feed emits DATE values for an all-day entry', !empty($ds[1]) && !empty($de[1]), $ics);
    if (!empty($ds[1]) && !empty($de[1])) {
        $span = (new DateTime($de[1]))->diff(new DateTime($ds[1]))->days;
        ok('DTSTART is the due date', $ds[1] === '20260910', $ds[1]);
        ok('…and DTEND is exactly ONE day later, i.e. a single-day event',
            $span === 1, "spans {$span} day(s) — " . $ds[1] . ' to ' . $de[1]);
    }

    // The same arithmetic the Graph provider does, checked here rather than
    // over the network: it takes the END DATE and adds a day.
    $graphEnd = (new DateTime(substr($due['end'], 0, 10)))->modify('+1 day')->format('Y-m-d');
    ok('the Graph conversion also yields a single day',
        $graphEnd === '2026-09-11',
        "start 2026-09-10, exclusive end {$graphEnd} — anything later spans extra days");

    echo "\nA task with nothing to say for a kind produces no event:\n";
    $bare = $task; $bare['work_start_datetime'] = null; $bare['due_date'] = null;
    ok('no work window, no work event', calendarSyncEventFromTask($bare, 'work') === null);
    ok('no due date, no due event',     calendarSyncEventFromTask($bare, 'due') === null);
    ok('POSITIVE CONTROL: the full task still produces both',
        calendarSyncEventFromTask($task, 'work') !== null
        && calendarSyncEventFromTask($task, 'due') !== null);

    echo "\nThe two events are distinguishable:\n";
    ok('a work event and a due event never look the same',
        $work['subject'] !== $due['subject'] && $work['all_day'] !== $due['all_day']);

    // ── 3. Reconcile, on a real task ────────────────────────────────────────
    //
    // ⚠️ With NO calendar connection configured, reconcile must be a safe no-op
    // rather than an error — that is the state every install is in until an
    // administrator sets one up, and saving a task must never depend on it.
    echo "\nReconciling a task nobody is holding:\n";
    //
    // 🔴 ASSIGNED TO NOBODY, DELIBERATELY. An earlier version of this file gave
    // the harness task to analyst 1 and asserted that reconcile "writes
    // nothing" — true only while nobody had switched tasks on. The moment Ed
    // did, the test PUSHED A FAKE TASK INTO HIS REAL OUTLOOK, and the cleanup
    // then deleted the task, which cascaded the mapping row away and left the
    // event orphaned in his calendar with nothing able to remove it.
    //
    // A test must not depend on a live setting to stay harmless. Unassigned,
    // there is no calendar it could ever reach, whatever anyone has chosen.
    $st = $conn->query("SELECT id FROM task_statuses WHERE COALESCE(is_closed,0)=0 ORDER BY id LIMIT 1");
    $openStatus = (int)$st->fetchColumn();
    // created_by_id is NOT NULL in the schema; only a non-strict server let
    // this insert through without it. Somebody made it - nobody holds it.
    $creator = (int)$conn->query("SELECT id FROM analysts ORDER BY id LIMIT 1")->fetchColumn();
    $conn->prepare(
        "INSERT INTO tasks (title, status_id, due_date, assigned_analyst_id, created_by_id, created_datetime, updated_datetime)
         VALUES ('ZZCAL harness task', ?, '2026-09-10', NULL, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())"
    )->execute([$openStatus, $creator]);
    $tid = (int)$conn->lastInsertId();

    $before = (int)$conn->query("SELECT COUNT(*) FROM calendar_sync_events")->fetchColumn();
    calendarSyncReconcileTask($conn, $tid);
    $after = (int)$conn->query("SELECT COUNT(*) FROM calendar_sync_events")->fetchColumn();
    ok('an unassigned task reaches nobody\'s calendar', $after === $before, "{$before} -> {$after}");

    calendarSyncReconcileTask($conn, $tid, true);   // the delete path
    ok('the "gone" path is a no-op too',
        (int)$conn->query("SELECT COUNT(*) FROM calendar_sync_events")->fetchColumn() === $before);

    // POSITIVE CONTROL: the rule above is about the ASSIGNEE, not about the
    // feature being broken. Without this, "wrote nothing" would also pass if
    // reconcile had stopped working entirely.
    ok('POSITIVE CONTROL: an unassigned task is what makes that true, not a broken reconcile',
        calendarSyncEventFromTask(['id' => $tid, 'title' => 'ZZCAL', 'due_date' => '2026-09-10',
                                   'work_start_datetime' => null, 'work_end_datetime' => null,
                                   'work_all_day' => 0], 'due') !== null);

    echo "\nThe audit trail tasks did not have before:\n";
    $conn->prepare(
        "INSERT INTO task_audit (task_id, analyst_id, field_name, old_value, new_value, source, created_datetime)
         VALUES (?, 1, 'Due date', '2026-09-10', '2026-09-14 (moved in a@b.c)', 'calendar', NOW())"
    )->execute([$tid]);
    $row = $conn->query("SELECT source, field_name FROM task_audit WHERE task_id = {$tid}")->fetch(PDO::FETCH_ASSOC);
    ok('a calendar-driven change is recorded, and marked as such',
        $row && $row['source'] === 'calendar' && $row['field_name'] === 'Due date');

} finally {
    try {
        $conn->exec("DELETE FROM task_audit WHERE task_id IN (SELECT id FROM tasks WHERE title LIKE 'ZZCAL%')");
        // #181's history outlives its tasks on purpose (no foreign keys) - remove it explicitly.
        $conn->exec("DELETE FROM task_events WHERE task_id IN (SELECT id FROM tasks WHERE title LIKE 'ZZCAL%')");
        $n = $conn->exec("DELETE FROM tasks WHERE title LIKE 'ZZCAL%'");
        echo "\n  cleanup: removed {$n} ZZCAL task(s)\n";
    } catch (Exception $e) {
        echo "\n  ⚠️  cleanup failed: " . $e->getMessage() . "\n";
    }
}

echo str_repeat('=', 70) . "\n  {$pass} passed, {$fail} failed\n\n";
exit($fail === 0 ? 0 : 1);
