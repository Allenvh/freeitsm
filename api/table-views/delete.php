<?php
/**
 * API: delete a saved view (discussion #96).
 * POST { id }
 *
 * Owner only. Deleting a view removes a way of looking at the table and nothing
 * else — no rows go with it.
 *
 * If it was somebody's personal default, their preference is left pointing at a
 * view that no longer exists. That is handled where it is read: a default that
 * cannot be loaded falls back to the table's own defaults, which is the same
 * thing that happens the first time anybody opens the table.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/table_views.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

try {
    $conn = connectToDatabase();
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    tableViewDelete($conn, (int)$_SESSION['analyst_id'], (int)($in['id'] ?? 0));
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
