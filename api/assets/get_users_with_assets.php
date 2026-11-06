<?php
/**
 * API: everyone currently holding at least one asset (discussion #56).
 *
 * GET ?search=<text>   optional, matches name or email
 *
 * Thin UI adapter over AssetsService::usersHoldingAssets(), which applies the
 * tenancy filter — so this returns only people holding assets the analyst is
 * allowed to see.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/assets.php';
require_once '../../includes/tenancy.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('assets');

try {
    $conn = connectToDatabase();
    $users = AssetsService::usersHoldingAssets(
        $conn,
        ActorContext::fromSession($conn),
        (string)($_GET['search'] ?? '')
    );
    echo json_encode(['success' => true, 'users' => $users]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
