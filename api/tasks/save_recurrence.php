<?php
/**
 * API: Tasks — set, change or remove the recurrence on a task (#94).
 *
 * POST { task_id, ...rule }   creates or updates the series this task belongs to
 * POST { task_id, off: true } stops it repeating, leaving every task already made
 *
 * Stopping a series never deletes anything. Occurrences that have already been
 * created are real work, some of it done; switching the repeat off means "no
 * more of these", not "pretend the last six months did not happen".
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/task_recurrence.php';
require_once '../../includes/services/task_history.php';   // rule versions (#181)

header('Content-Type: application/json');

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tasks');
requireModuleWriteJson('tasks');

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

$taskId = (int)($in['task_id'] ?? 0);
if ($taskId <= 0) {
    echo json_encode(['success' => false, 'error' => 'A task is required']);
    exit;
}

try {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $t = $conn->prepare("SELECT id, due_date, recurrence_id, parent_task_id FROM tasks WHERE id = ?");
    $t->execute([$taskId]);
    $task = $t->fetch(PDO::FETCH_ASSOC);
    // 🔒 Company scope, as every other task endpoint: another company's task is
    // answered exactly like one that does not exist, so its existence is not
    // confirmed either.
    if (!$task || !analystCanAccessTask($conn, (int)$_SESSION['analyst_id'], $taskId)) {
        echo json_encode(['success' => false, 'error' => 'No such task']);
        exit;
    }
    // A subtask repeating on its own would produce orphans with a parent that
    // was never repeated. The series belongs to the piece of work, not a step
    // inside it.
    if (!empty($task['parent_task_id'])) {
        echo json_encode(['success' => false, 'error' => 'A subtask cannot repeat on its own. Set the recurrence on the task it belongs to.']);
        exit;
    }

    // ---- Switching it off -------------------------------------------------
    if (!empty($in['off'])) {
        if (!empty($task['recurrence_id'])) {
            $off = $conn->prepare("UPDATE task_recurrences SET is_active = 0, updated_datetime = UTC_TIMESTAMP() WHERE id = ? AND is_active = 1");
            $off->execute([(int)$task['recurrence_id']]);
            // #181: stopping is a version of the rule, with who stopped it. The
            // evidence report shows the stopped period as stopped, not as missing.
            if ($off->rowCount() > 0) {
                TaskHistory::recordRuleVersion($conn, (int)$task['recurrence_id'], 'stopped', ActorContext::fromSession($conn));
            }
        }
        echo json_encode(['success' => true, 'recurrence' => null]);
        exit;
    }

    // ---- Validate ---------------------------------------------------------
    // Shared with the preview endpoint. Two places sanitising the same input
    // differently is how a preview ends up showing dates the worker will not
    // produce, so there is exactly one home for it.
    $rule = TaskRecurrence::ruleFromInput($in);
    // GH #190 phase 4's columns exist only after Database Verification; until then they are not written.
    if (!TaskRecurrence::hasPeriodColumns($conn)) unset($rule['period_mode'], $rule['due_offset_mode'], $rule['due_offset_days']);
    $mode = $rule['mode'];

    // A rule that cannot produce a date is refused HERE rather than saved and
    // found to be inert later. The engine is the authority on that, so ask it.
    $anchor = $task['due_date'] ?: gmdate('Y-m-d');
    $probe  = TaskRecurrence::nextDate($rule, $anchor);
    if ($probe === null) {
        echo json_encode(['success' => false, 'error' => 'Those settings never produce another date. Check the day or weekday you have chosen.']);
        exit;
    }

    // For a fixed schedule the worker starts from the occurrence AFTER this one.
    // Seeding it with this task's own due date would have the first run produce
    // a duplicate of the task in front of you.
    $rule['next_due_date'] = $mode === 'schedule' ? $probe : null;

    // Was this series switched off? Saving it again is how a repeat is resumed.
    $wasActive = null;
    if (!empty($task['recurrence_id'])) {
        $a = $conn->prepare("SELECT is_active FROM task_recurrences WHERE id = ?");
        $a->execute([(int)$task['recurrence_id']]);
        $v = $a->fetchColumn();
        $wasActive = $v === false ? null : (bool)$v;
    }

    $conn->beginTransaction();

    if (!empty($task['recurrence_id'])) {
        $sets = [];
        $args = [];
        foreach ($rule as $col => $val) { $sets[] = "`$col` = ?"; $args[] = $val; }
        $sets[] = 'is_active = 1';
        $sets[] = 'updated_datetime = UTC_TIMESTAMP()';
        $args[] = (int)$task['recurrence_id'];
        $conn->prepare("UPDATE task_recurrences SET " . implode(', ', $sets) . " WHERE id = ?")->execute($args);
        $recurrenceId = (int)$task['recurrence_id'];
    } else {
        $cols = array_keys($rule);
        $conn->prepare(
            "INSERT INTO task_recurrences (`" . implode('`, `', $cols) . "`, created_by_id, created_datetime, updated_datetime)
             VALUES (" . rtrim(str_repeat('?, ', count($cols)), ', ') . ", ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())"
        )->execute(array_merge(array_values($rule), [(int)$_SESSION['analyst_id']]));
        $recurrenceId = (int)$conn->lastInsertId();

        // This task becomes the first occurrence AND the master of the series.
        $conn->prepare("UPDATE tasks SET recurrence_id = ?, recurrence_master_id = COALESCE(recurrence_master_id, id) WHERE id = ?")
             ->execute([$recurrenceId, $taskId]);
    }

    $conn->commit();

    // #181: every save is a version of the rule - who changed it and when - so the
    // evidence report can work out which dates were expected under the rule that
    // applied at the time. The task the series starts from is its first occurrence,
    // scheduled for its own due date.
    $kind = $wasActive === null ? 'created' : ($wasActive ? 'changed' : 'resumed');
    TaskHistory::recordRuleVersion($conn, $recurrenceId, $kind, ActorContext::fromSession($conn));
    if ($kind === 'created' && !empty($task['due_date'])) {
        try {
            $conn->prepare("UPDATE tasks SET scheduled_date = COALESCE(scheduled_date, ?) WHERE id = ?")->execute([$task['due_date'], $taskId]);
        } catch (Throwable $e) { /* before Database Verification */ }
    }

    echo json_encode([
        'success'    => true,
        'recurrence' => ['id' => $recurrenceId] + $rule,
        'next_date'  => $probe,
    ]);

} catch (Throwable $e) {
    if (isset($conn) && $conn->inTransaction()) $conn->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
