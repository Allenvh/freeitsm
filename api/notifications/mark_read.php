<?php
/**
 * API: mark notifications read.
 *
 * POST { ids: [1,2,3] }  — mark those
 * POST { all: true }     — mark everything unread
 *
 * Always scoped to the signed-in analyst, so ids belonging to somebody else are
 * simply not matched by the UPDATE rather than being rejected — there is nothing
 * to leak either way, and it keeps a stale open tab from erroring.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/notifications.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $conn      = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];
    $in        = json_decode(file_get_contents('php://input'), true) ?: [];

    if (!empty($in['all'])) {
        $marked = NotificationsService::markAllRead($conn, $analystId);
    } elseif (isset($in['ids']) && is_array($in['ids'])) {
        $marked = NotificationsService::markRead($conn, $analystId, $in['ids']);
    } else {
        throw new Exception("Either 'ids' or 'all' is required.");
    }

    echo json_encode([
        'success' => true,
        'marked'  => $marked,
        'unread'  => NotificationsService::unreadCount($conn, $analystId),
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
