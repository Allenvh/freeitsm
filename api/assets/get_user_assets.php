<?php
/**
 * API: one person and everything currently assigned to them (discussion #56).
 *
 * GET ?user_id=<int>
 *
 * Feeds both the on-screen overview and the handover document, so the two can
 * never disagree about what somebody holds.
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
    $userId = (int)($_GET['user_id'] ?? 0);
    if ($userId <= 0) {
        throw new Exception('user_id is required');
    }

    $conn   = connectToDatabase();
    $result = AssetsService::assetsForUser($conn, ActorContext::fromSession($conn), $userId);

    if ($result === null) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Unknown user']);
        exit;
    }

    echo json_encode([
        'success' => true,
        'user'    => $result['user'],
        'assets'  => $result['assets'],
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
