<?php
/**
 * API: Delete a workflow. Execution rows for the deleted workflow stay
 * in workflow_executions (no FK cascade configured) so the audit trail
 * survives — they just become orphaned on workflow_id. The list query
 * filters them by joining only with workflows that still exist.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();
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
    // GH #209: another company's workflow is not found; a shared one needs
    // someone who can see every company.
    require_once __DIR__ . '/../../includes/workflow_tenancy.php';
    $analystId = (int)$_SESSION['analyst_id'];
    $wf = workflowRowTenant($conn, $id);
    if (!$wf || !analystCanReadWorkflow($conn, $analystId, $wf)) {
        echo json_encode(['success' => false, 'error' => 'Workflow not found']);
        exit;
    }
    if (!analystCanWriteWorkflowFor($conn, $analystId, $wf['tenant_id'])) {
        echo json_encode(['success' => false, 'error' => 'This workflow runs for every company, so only someone who can see every company can delete it.']);
        exit;
    }
    $stmt = $conn->prepare("DELETE FROM workflows WHERE id = ?");
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
