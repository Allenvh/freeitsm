<?php
/**
 * API: add a free-text journal note to a problem. Records who (the session
 * analyst), when (now) and the note text. Gated by analystCanAccessProblem.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }

try {
    $d = json_decode(file_get_contents('php://input'), true);
    $problemId = (int) ($d['problem_id'] ?? 0);
    $note = trim($d['note'] ?? '');
    if ($problemId <= 0) throw new Exception('Problem ID is required');
    if ($note === '') throw new Exception('Note cannot be empty');

    $conn = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];
    if (!analystCanAccessProblem($conn, $analystId, $problemId)) throw new Exception('Problem not found');

    $conn->prepare("INSERT INTO problem_notes (problem_id, analyst_id, note, created_datetime) VALUES (?, ?, ?, UTC_TIMESTAMP())")
         ->execute([$problemId, $analystId, $note]);
    $conn->prepare("UPDATE problems SET updated_datetime = UTC_TIMESTAMP() WHERE id = ?")->execute([$problemId]);

    echo json_encode(['success' => true, 'message' => 'Note added']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
