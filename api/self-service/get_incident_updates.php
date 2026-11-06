<?php
/**
 * API: the external updates on one incident, for the self-service portal (#99).
 * GET ?incident_id=
 *
 * Loaded on demand when somebody expands an incident, because the requester
 * asked for the history to be collapsed by default and most people reading a
 * status page never open one.
 *
 * 🔴 The `is_internal = 0` filter is NOT here. It lives in
 * ssPortalIncidentUpdates(), which is the only thing this endpoint calls, so
 * there is exactly one place that decides what an end user may read — and it is
 * the same place the dashboard uses. An endpoint that did its own filtering
 * would be a second copy of that rule, and the second copy is the one that gets
 * it wrong.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/service_status_portal.php';

header('Content-Type: application/json');

// A portal session, not an analyst one. Somebody signed out gets nothing, the
// same as they do for their own tickets.
if (!isset($_SESSION['ss_user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

try {
    $incidentId = (int)($_GET['incident_id'] ?? 0);
    if ($incidentId <= 0) {
        throw new Exception('incident_id is required');
    }

    $conn = connectToDatabase();
    // GH #217: nothing from a module switched off for the install.
    requireModuleOnJson($conn, 'service-status');

    // ⚠️ No "does this incident exist" check, deliberately. An incident with no
    // external updates and an incident that does not exist both come back
    // empty, so asking about an id cannot tell you whether it is real.
    echo json_encode([
        'success' => true,
        'updates' => ssPortalIncidentUpdates($conn, $incidentId),
    ]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
