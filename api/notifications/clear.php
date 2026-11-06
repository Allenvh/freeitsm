<?php
/**
 * API: remove notifications from the bell (discussion #111).
 *
 * POST { ids: [1,2,3] }                      — clear those
 * POST { all: true }                         — clear everything ALREADY READ
 * POST { all: true, include_unread: true }   — clear everything, unread included
 *
 * A hard DELETE. Mark-as-read and clear are deliberately separate actions:
 * "read" silences the badge and keeps the row, "clear" removes it. Before this
 * existed, Mark all read left every row sitting in the panel for good, which is
 * what the discussion reported.
 *
 * ⚠️ include_unread must be asked for explicitly. Defaulting it off is the safety
 * catch — one click on Clear all should never bin news nobody has read yet.
 *
 * Always scoped to the signed-in analyst, so ids belonging to somebody else are
 * simply not matched by the DELETE rather than being rejected — nothing leaks
 * either way, and a stale open tab does not error.
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
        $cleared = NotificationsService::clearAll($conn, $analystId, !empty($in['include_unread']));
    } elseif (isset($in['ids']) && is_array($in['ids'])) {
        $cleared = NotificationsService::clear($conn, $analystId, $in['ids']);
    } else {
        throw new Exception("Either 'ids' or 'all' is required.");
    }

    echo json_encode([
        'success' => true,
        'cleared' => $cleared,
        'unread'  => NotificationsService::unreadCount($conn, $analystId),
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
