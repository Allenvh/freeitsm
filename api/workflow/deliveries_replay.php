<?php
/**
 * API: replay one webhook delivery — resets it to 'pending' so the cron worker
 * sends it again. Body: { id }.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/webhook_delivery.php';
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('workflow');

$in = json_decode(file_get_contents('php://input'), true);
$id = isset($in['id']) ? (int)$in['id'] : 0;
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Missing id']);
    exit;
}

try {
    $conn = connectToDatabase();

    // GH #209. Replay sends the stored payload again, so it needs both: the run
    // it came from must be one this analyst can read, and the workflow one they
    // could fire. Another company's delivery is not found.
    require_once __DIR__ . '/../../includes/workflow_tenancy.php';
    if (workflowTenancyActive($conn)) {
        $aid = (int)$_SESSION['analyst_id'];
        $d = $conn->prepare(
            "SELECT d.id, w.id AS wf_id, w.tenant_id AS wf_tenant, e.tenant_id AS run_tenant
               FROM webhook_deliveries d
               LEFT JOIN workflows w ON w.id = d.workflow_id
               LEFT JOIN workflow_executions e ON e.id = d.execution_id
              WHERE d.id = ?"
        );
        $d->execute([$id]);
        $row = $d->fetch(PDO::FETCH_ASSOC);
        $runTenant = ($row && $row['run_tenant'] !== null) ? (int)$row['run_tenant'] : null;
        $wfTenant  = ($row && $row['wf_tenant'] !== null) ? (int)$row['wf_tenant'] : null;
        if (!$row || !analystCanReadWorkflowRun($conn, $aid, $runTenant)
            || ($row['wf_id'] !== null && !analystCanWriteWorkflowFor($conn, $aid, $wfTenant))) {
            echo json_encode(['success' => false, 'error' => 'Delivery not found or not replayable']);
            exit;
        }
    }
    $ok = webhookReplay($conn, $id);
    // Replay re-sends the STORED body. If retention purged it there is nothing to
    // send, and re-queueing would POST an empty payload to a live endpoint — so
    // say why rather than failing vaguely.
    echo json_encode([
        'success' => $ok,
        'error'   => $ok ? null : (webhookReplayBlockedReason($conn, $id) ?? 'Delivery not found or not replayable'),
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
