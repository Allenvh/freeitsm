<?php
/**
 * API: skip an occurrence of a repeating task, with a reason (#181 Part D).
 *
 * Thin UI adapter over TasksService::skipOccurrence(): the occurrence closes as
 * SKIPPED - into the status Tasks -> Settings -> Evidence names - and in a repeat
 * set to "after completion" the next one is made, as completing it would. The
 * evidence report shows it as skipped, with the reason.
 *
 * @input id, reason  POST JSON
 * @output JSON {success, status_id}
 * @wiki Repeating-Task-Evidence-Developer-Guide
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/tasks.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tasks');
requireModuleWriteJson('tasks');

try {
    $conn = connectToDatabase();
    $in = json_decode(file_get_contents('php://input'), true) ?: [];
    $r = TasksService::skipOccurrence($conn, ActorContext::fromSession($conn), (int)($in['id'] ?? 0), (string)($in['reason'] ?? ''));
    echo json_encode(['success' => true, 'status_id' => $r['status_id']]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
