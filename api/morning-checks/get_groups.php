<?php
/**
 * API: list morning check groups (discussion #64).
 *
 * Read-only and ungated beyond module access: the dashboard needs group names
 * and routing to draw its headings, and every analyst sees the dashboard.
 * Editing them is what needs the capability — see save_group.php.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/morning_checks.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('morning-checks');

try {
    $conn = connectToDatabase();
    $activeOnly = !empty($_GET['active_only']);
    echo json_encode(['success' => true, 'groups' => MorningChecksService::listGroups($conn, $activeOnly)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
