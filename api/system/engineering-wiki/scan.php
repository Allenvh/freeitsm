<?php
/**
 * API: run the Engineering wiki scanner for a few seconds (the Rescan button).
 * GET  - when the last scan finished and what it did
 * POST - do up to ~6 seconds of scanning; call again until done is true. {fresh:true} restarts from scratch.
 * Returns: { success, done, phase, done_files, total_files, changed, pages, last }
 *
 * @input  fresh  true to forget a half-finished scan and start again
 * @output progress - the browser keeps calling until done is true, so no request runs long
 */
session_start(['read_and_close' => true]);
require_once '../../../config.php';
require_once '../../../includes/admin_api_guard.php';   // administrators only
require_once '../../../includes/engineering_wiki/scanner.php';

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => true, 'last' => EwScanner::lastScan()]);
    exit;
}
try {
    @set_time_limit(120);
    $in = json_decode((string)file_get_contents('php://input'), true) ?: [];
    $r = (new EwScanner())->step(6.0, !empty($in['fresh']));
    echo json_encode(['success' => true] + $r + ['last' => $r['done'] ? EwScanner::lastScan() : null]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
