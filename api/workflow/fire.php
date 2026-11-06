<?php
/**
 * API: Manually fire a workflow with a synthetic payload — the "Test fire"
 * button in the editor. Lets a user verify the engine end-to-end without
 * waiting for a real event from the host module.
 *
 * Body: { id, payload?: object, dry_run?: bool }
 *
 * With dry_run, the engine evaluates the conditions for real but does NOT
 * execute the actions — it records what each one would have done, with the
 * {{variables}} already substituted. Safe to run against a live workflow.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once __DIR__ . '/../../workflow/includes/engine.php';

// Runs real actions: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('workflow');

$in = json_decode(file_get_contents('php://input'), true);
$id = isset($in['id']) ? (int)$in['id'] : 0;
$payload = is_array($in['payload'] ?? null) ? $in['payload'] : [];
$dryRun  = !empty($in['dry_run']);
if (!$id) {
    echo json_encode(['success' => false, 'error' => 'Missing id']);
    exit;
}

try {
    // GH #209. Firing runs the actions, so it needs the same right as changing
    // the workflow: a shared one runs for every company's records.
    $conn = connectToDatabase();
    $analystId = (int)$_SESSION['analyst_id'];
    $wf = workflowRowTenant($conn, $id);
    if (!$wf || !analystCanReadWorkflow($conn, $analystId, $wf)) {
        echo json_encode(['success' => false, 'error' => 'Workflow not found']);
        exit;
    }
    if (!analystCanWriteWorkflowFor($conn, $analystId, $wf['tenant_id'])) {
        echo json_encode(['success' => false, 'error' => 'This workflow runs for every company, so only someone who can see every company can test it.']);
        exit;
    }
    $result = WorkflowEngine::manualFire($id, $payload, $dryRun);
    echo json_encode(['success' => true, 'result' => $result]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
