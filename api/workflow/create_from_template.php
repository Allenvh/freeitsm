<?php
/**
 * API: Clone a starter template into a real workflow for this install.
 *
 * Body: { key }
 *
 * The new workflow is always created INACTIVE — a recipe must be read and
 * switched on deliberately, never fire the moment it is cloned.
 *
 * Returns the new id plus `unresolved`: the list of args the template could
 * not fill in for this install (a priority it doesn't have, a webhook URL
 * only the user knows). The editor turns that into a banner.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once __DIR__ . '/../../workflow/includes/templates.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();
header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('workflow');

$in  = json_decode(file_get_contents('php://input'), true);
$key = trim((string)($in['key'] ?? ''));
if ($key === '') {
    echo json_encode(['success' => false, 'error' => 'Missing template key']);
    exit;
}

$tpl = WorkflowTemplates::get($key);
if (!$tpl) {
    echo json_encode(['success' => false, 'error' => 'Unknown template']);
    exit;
}

try {
    $wf = WorkflowTemplates::resolve($tpl);

    $conn = connectToDatabase();

    // GH #209. The clone's company: the one asked for, else shared for someone
    // who can see every company and the switched-to company for anyone else -
    // the same rules as saving (save.php).
    require_once __DIR__ . '/../../includes/workflow_tenancy.php';
    $analystId = (int)$_SESSION['analyst_id'];
    $tenancyOn = workflowTenancyActive($conn);
    $tenant = null;
    if ($tenancyOn) {
        if (array_key_exists('company_id', $in)) {
            [$ok, $tenant, $err] = workflowParseCompany($conn, $in['company_id']);
            if (!$ok) { echo json_encode(['success' => false, 'error' => $err]); exit; }
        } else {
            $tenant = analystHasAllTenantAccess($conn, $analystId) ? null : getActiveTenantId($conn, $analystId);
        }
        if (!analystCanWriteWorkflowFor($conn, $analystId, $tenant)) {
            echo json_encode(['success' => false, 'error' => 'You cannot make a workflow for that company.']);
            exit;
        }
    }
    $tCol = $tenancyOn ? ', tenant_id' : '';
    $stmt = $conn->prepare(
        "INSERT INTO workflows
         (name, description, trigger_event, conditions, actions, is_active, created_by, created_datetime, updated_datetime$tCol)
         VALUES (?, ?, ?, ?, ?, 0, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP()" . ($tenancyOn ? ', ?' : '') . ")"
    );
    $vals = [
        $wf['name'],
        $wf['description'],
        $wf['trigger_event'],
        json_encode($wf['conditions']),
        json_encode($wf['actions']),
        $analystId,
    ];
    if ($tenancyOn) $vals[] = $tenant;
    $stmt->execute($vals);

    echo json_encode([
        'success'    => true,
        'id'         => (int)$conn->lastInsertId(),
        'unresolved' => $wf['unresolved'],
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
