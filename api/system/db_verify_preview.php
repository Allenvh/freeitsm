<?php
/**
 * API Endpoint: Database Verification — preview (READ ONLY).
 *
 * Answers "what would Database Verification change?" without changing anything.
 * See includes/db_verify_preview.php for what this can and cannot promise.
 *
 * Deliberately a separate endpoint rather than a ?dry_run=1 flag on db_verify.php:
 * a flag on the destructive endpoint is one typo away from running the real thing,
 * and it would mean the read-only path and the migration path share a file that
 * exec()s DDL. This file contains no ALTER, no CREATE and no DROP at all, which is
 * a property you can verify by reading it rather than by trusting a conditional.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/setup_state.php';
require_once '../../includes/db_verify_preview.php';

header('Content-Type: application/json');

try {
    $conn = connectToDatabase();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit;
}

// Same door as db_verify.php itself: administrators, unless this is a genuinely
// unprovisioned install where no analyst exists yet to be one.
if (!installIsUnprovisioned($conn)) {
    if (!isset($_SESSION['analyst_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Not authenticated']);
        exit;
    }
    require_once '../../includes/admin_api_guard.php';
}

try {
    $schema = require __DIR__ . '/../../includes/db_verify_schema.php';
    $dbName = DB_NAME;

    $preview = dbVerifyPreview($conn, $schema, $dbName);

    echo json_encode([
        'success' => true,
        'preview' => $preview,
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
