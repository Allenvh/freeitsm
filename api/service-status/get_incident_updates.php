<?php
/**
 * API: the update thread for one incident (discussion #59, phase 2).
 *
 * GET ?incident_id=21
 *
 * The rows that make a status page readable: what was said, when, by whom, and
 * which services were at which impact at that moment. See
 * includes/services/service_uptime.php for how the same rows are read back as
 * per-service downtime.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('service-status');

try {
    $conn = connectToDatabase();
    $incidentId = (int)($_GET['incident_id'] ?? 0);
    if ($incidentId <= 0) {
        echo json_encode(['success' => false, 'error' => 'No incident.']);
        exit;
    }

    // An incident raised before phase 2 has no updates. That is not an error and
    // must not read as one — the caller shows "no updates recorded" rather than
    // a failure, because the incident itself is perfectly valid.
    try {
        $stmt = $conn->prepare(
            // ⚠️ This is the ANALYST view, so it returns both kinds and says
            // which is which. The portal has its own reader
            // (includes/service_status_portal.php) that filters to external —
            // filtering here instead would hide internal notes from the people
            // who wrote them.
            "SELECT u.id, u.created_datetime, u.comment, u.is_internal,
                    sst.name AS status, sst.colour AS status_colour,
                    a.full_name AS author
               FROM status_incident_updates u
               LEFT JOIN service_incident_statuses sst ON sst.id = u.status_id
               LEFT JOIN analysts a ON a.id = u.created_by_id
              WHERE u.incident_id = ?
              ORDER BY u.created_datetime ASC, u.id ASC"
        );
        $stmt->execute([$incidentId]);
        $updates = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        echo json_encode(['success' => true, 'updates' => [], 'available' => false]);
        exit;
    }

    if ($updates) {
        $svc = $conn->prepare(
            "SELECT sus.update_id, ss.name AS service, il.name AS impact, il.colour AS colour
               FROM status_incident_update_services sus
               JOIN status_services ss ON ss.id = sus.service_id
               LEFT JOIN service_impact_levels il ON il.id = sus.impact_level_id
              WHERE sus.update_id IN (" . implode(',', array_fill(0, count($updates), '?')) . ")
              ORDER BY ss.name"
        );
        $svc->execute(array_column($updates, 'id'));
        $byUpdate = [];
        foreach ($svc->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $byUpdate[(int)$r['update_id']][] = [
                'service' => $r['service'],
                'impact'  => $r['impact'],
                'colour'  => $r['colour'],
            ];
        }
        foreach ($updates as &$u) {
            $u['services'] = $byUpdate[(int)$u['id']] ?? [];
        }
        unset($u);
    }

    echo json_encode(['success' => true, 'updates' => $updates, 'available' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
