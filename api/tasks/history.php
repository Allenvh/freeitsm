<?php
/**
 * API: a task's history (#181) - the History tab in the task window.
 *
 * Its events (created, status changes, completed by whom, reopened, date and
 * assignee changes, skipped, successor expected, checklists attached / removed),
 * the older task_audit rows (company moves, checklist overrides, calendar sync)
 * and every checklist step change, newest first. Read-only; Read only access to
 * Tasks may read it.
 *
 * @input task_id  GET: the task
 * @output JSON {success, history: [{at, kind, type, who, actor_kind, from, to, detail}]}
 * @wiki Repeating-Task-Evidence-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/services/task_history.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tasks');

try {
    $conn = connectToDatabase();
    $taskId = (int)($_GET['task_id'] ?? 0);
    // 🔒 Company scope - another company's task is answered as one that does not exist.
    if ($taskId <= 0 || !analystCanAccessTask($conn, (int)$_SESSION['analyst_id'], $taskId)) {
        echo json_encode(['success' => false, 'error' => 'Task not found']);
        exit;
    }
    echo json_encode(['success' => true, 'history' => TaskHistory::forTask($conn, $taskId)]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
