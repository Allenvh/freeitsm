<?php
/**
 * API: Tasks — future occurrences of fixed-schedule repeats (#94).
 *
 * GET ?from=YYYY-MM-DD&to=YYYY-MM-DD
 *
 * Returns dates a repeat WILL land on, computed from the rule. Nothing is
 * created and nothing is written; these are drawn faintly on the calendar so a
 * plan is visible before the work exists.
 *
 * Only fixed schedules appear here. A repeat that fires on completion has no
 * predictable future — its next date is counted from the day somebody finishes
 * the current one — so projecting it would be inventing information.
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

try {
    $conn = connectToDatabase();

    $from = substr((string)($_GET['from'] ?? ''), 0, 10) ?: gmdate('Y-m-d');
    $to   = substr((string)($_GET['to']   ?? ''), 0, 10) ?: gmdate('Y-m-d', strtotime('+1 year'));
    if ($to < $from) { $to = $from; }

    // A year is more than any calendar view asks for, and stops a hand-made URL
    // walking a daily rule for a decade.
    if (strtotime($to) - strtotime($from) > 400 * 86400) {
        $to = gmdate('Y-m-d', strtotime($from) + 400 * 86400);
    }

    echo json_encode([
        'success'   => true,
        'projected' => TaskRecurrence::project($conn, $from, $to, 60, (int)$_SESSION['analyst_id']),
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
