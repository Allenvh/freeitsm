<?php
/**
 * API: Tasks — what a repeat rule WOULD produce, without saving it (#94).
 *
 * POST { task_id, ...rule }
 *
 * Reads nothing and writes nothing. It takes the settings as they currently
 * stand in the editor — saved or not — and hands back the dates, so somebody
 * can see what "every second Tuesday of the month, 5 times" actually means
 * before committing to it.
 *
 * ⚠️ The dates come from TaskRecurrence, the same engine the cron uses, and the
 * input is sanitised by the same TaskRecurrence::ruleFromInput() the save
 * endpoint uses. Working them out here — or in JavaScript, which would be
 * faster and is the obvious temptation — would give two implementations that
 * drift, and a preview that disagrees with what actually happens is worse than
 * no preview.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/task_recurrence.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tasks');

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

    $t = $conn->prepare("SELECT id, title, start_date, due_date, recurrence_id FROM tasks WHERE id = ?");
    $t->execute([$taskId]);
    $task = $t->fetch(PDO::FETCH_ASSOC);
    // 🔒 Company scope, as every other task endpoint: another company's task is
    // answered exactly like one that does not exist, so its existence is not
    // confirmed either.
    if (!$task || !analystCanAccessTask($conn, (int)$_SESSION['analyst_id'], $taskId)) {
        echo json_encode(['success' => false, 'error' => 'No such task']);
        exit;
    }

    $rule = TaskRecurrence::ruleFromInput($in);

    // Refused for the same reason saving is refused, and in the same words, so
    // a rule that cannot produce a date says so at the point of previewing
    // rather than looking like an empty list.
    $anchor = $task['due_date'] ?: gmdate('Y-m-d');
    if (TaskRecurrence::nextDate($rule, $anchor) === null) {
        echo json_encode([
            'success' => false,
            'error'   => 'Those settings never produce another date. Check the day or weekday you have chosen.',
        ]);
        exit;
    }

    $preview = TaskRecurrence::previewRule($rule, $task, 25);

    // Occurrences that already exist are marked, so previewing an established
    // series shows what it has actually done as well as what it will do.
    $existing = [];
    if (!empty($task['recurrence_id'])) {
        $e = $conn->prepare(
            "SELECT id, due_date FROM tasks
              WHERE recurrence_id = ? AND parent_task_id IS NULL AND due_date IS NOT NULL"
        );
        $e->execute([(int)$task['recurrence_id']]);
        foreach ($e->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $existing[substr((string)$row['due_date'], 0, 10)] = (int)$row['id'];
        }
    }
    foreach ($preview['occurrences'] as &$occ) {
        $occ['task_id'] = $existing[$occ['due_date']] ?? null;
        $occ['exists']  = $occ['task_id'] !== null;
    }
    unset($occ);

    echo json_encode([
        'success'     => true,
        'mode'        => $rule['mode'],
        'title'       => $task['title'],
        'today'       => gmdate('Y-m-d'),
        'occurrences' => $preview['occurrences'],
        'truncated'   => $preview['truncated'],
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
