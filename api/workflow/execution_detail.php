<?php
/**
 * API: one execution, in full — the step-by-step log plus the trigger payload
 * snapshot. This is the "why did it do that?" view.
 *
 * The payload snapshot is the genuinely valuable bit: it's exactly what the
 * conditions were evaluated against and what the {{variables}} resolved from,
 * captured at run time. Without it you're guessing at what the event looked
 * like when it fired.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('workflow');

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Missing id']);
    exit;
}

try {
    $conn = connectToDatabase();
    $stmt = $conn->prepare(
        "SELECT e.*, w.name AS current_name
           FROM workflow_executions e
           LEFT JOIN workflows w ON w.id = e.workflow_id
          WHERE e.id = ? LIMIT 1"
    );
    $stmt->execute([$id]);
    $e = $stmt->fetch(PDO::FETCH_ASSOC);
    // GH #209: a run for a company this analyst cannot see is not there for them.
    require_once __DIR__ . '/../../includes/workflow_tenancy.php';
    $runTenant = ($e && isset($e['tenant_id']) && $e['tenant_id'] !== null) ? (int)$e['tenant_id'] : null;
    if (!$e || !analystCanReadWorkflowRun($conn, (int)$_SESSION['analyst_id'], $runTenant)) {
        echo json_encode(['success' => false, 'error' => 'That run no longer exists.']);
        exit;
    }

    // Any webhook deliveries this run enqueued — so a failed chat alert is one
    // click from the run that produced it, rather than a hunt through two logs.
    $deliveries = [];
    try {
        $d = $conn->prepare(
            "SELECT id, preset, status, attempts, last_status_code, last_error
               FROM webhook_deliveries WHERE execution_id = ? ORDER BY id"
        );
        $d->execute([$id]);
        $deliveries = $d->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $ignore) { /* table may predate this */ }

    echo json_encode([
        'success' => true,
        'execution' => [
            'id'          => (int)$e['id'],
            'workflow_id' => $e['workflow_id'] !== null ? (int)$e['workflow_id'] : null,
            'workflow'    => $e['current_name'] ?: ($e['workflow_name'] ?: '(deleted workflow)'),
            'trigger'     => $e['trigger_event'],
            'status'      => $e['status'],
            'is_dry_run'  => (int)$e['is_dry_run'],
            'started'     => $e['started_datetime'],
            'finished'    => $e['finished_datetime'],
            'error'       => $e['error_message'],
            'step_log'    => json_decode($e['step_log'] ?: '[]', true) ?: [],
            'payload'     => json_decode($e['trigger_payload'] ?: '{}', true) ?: [],
        ],
        'deliveries' => array_map(fn($r) => [
            'id'          => (int)$r['id'],
            'preset'      => $r['preset'],
            'status'      => $r['status'],
            'attempts'    => (int)$r['attempts'],
            'last_status' => $r['last_status_code'] !== null ? (int)$r['last_status_code'] : null,
            'last_error'  => $r['last_error'],
        ], $deliveries),
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
