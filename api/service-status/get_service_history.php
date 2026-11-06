<?php
/**
 * API: one service's status history and uptime (discussion #59).
 *
 * GET ?service_id=3&days=90
 *
 * Everything here is DERIVED from incidents — see includes/services/service_uptime.php
 * for why there is no history table to read, and for what this cannot yet see
 * (changes made DURING an incident).
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/service_uptime.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('service-status');

try {
    $conn = connectToDatabase();

    $serviceId = (int)($_GET['service_id'] ?? 0);
    if ($serviceId <= 0) {
        echo json_encode(['success' => false, 'error' => 'No service.']);
        exit;
    }

    // The window is a choice from a fixed list, never a free number: it drives a
    // DATE_SUB interval and the size of the strip we build, and "?days=100000"
    // is a way to ask the server to loop a hundred thousand times.
    $days = (int)($_GET['days'] ?? 0);
    if (!in_array($days, ServiceUptime::WINDOWS, true)) {
        $days = ServiceUptime::defaultWindowDays($conn);
    }

    $svc = $conn->prepare("SELECT id, name, description FROM status_services WHERE id = ?");
    $svc->execute([$serviceId]);
    $service = $svc->fetch(PDO::FETCH_ASSOC);
    if (!$service) {
        echo json_encode(['success' => false, 'error' => 'Service not found.']);
        exit;
    }

    $incidents = ServiceUptime::incidentsFor($conn, $serviceId, $days);

    // Durations are formatted server-side so the table, the strip tooltip and any
    // future export all read identically — one implementation of "2h 15m".
    foreach ($incidents as &$i) {
        $i['duration'] = ServiceUptime::humanDuration($i['seconds']);
    }
    unset($i);

    echo json_encode([
        'success'   => true,
        'service'   => $service,
        'windows'   => ServiceUptime::WINDOWS,
        'summary'   => ServiceUptime::summaryFor($conn, $serviceId, $days),
        'incidents' => $incidents,
        'strip'     => ServiceUptime::dailyStrip($conn, $serviceId, $days),
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
