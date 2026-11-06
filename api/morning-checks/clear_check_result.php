<?php
/**
 * API: put a check back to "not checked" (discussion #64).
 *
 * For the everyday case of clicking the wrong status button. See
 * MorningChecksService::clearResult() for why this removes the row rather than
 * nulling its status, and for what else goes with it.
 *
 * ⚠️ No ownership check, deliberately — consistent with saving a result. Who a
 * check is routed to is guidance, so correcting somebody else's mis-click is
 * exactly as allowed as doing their check for them while they are off.
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
    $in   = json_decode(file_get_contents('php://input'), true) ?: [];
    $checkId = (int)($in['checkId'] ?? 0);
    if ($checkId <= 0) {
        echo json_encode(['success' => false, 'error' => 'No check.']);
        exit;
    }
    $cleared = MorningChecksService::clearResult(
        $conn,
        ActorContext::fromSession($conn),
        $checkId,
        isset($in['date']) ? (string)$in['date'] : null
    );
    echo json_encode(['success' => true, 'cleared' => $cleared]);
} catch (ServiceError $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
