<?php
/**
 * API: move a waiting catalogue request to another team or person (GH #220).
 * Administrators only - the way out for a request that is stuck (its team has
 * emptied, or the votes have split with nobody left to decide).
 *
 * POST { submission_id, team_id?, approvals_required?, rejections_required?, approver_id?, reason }
 *   -> { success }
 *
 * Exactly one of team_id / approver_id. The reason is required and recorded with the
 * move; votes already cast stay on record but stop counting (a new round starts).
 * See catalogueApprovalMove() in includes/catalogue_approvals.php.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/catalogue_approvals.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }

try {
    $conn = connectToDatabase();
    if (!catalogueApproverCanEnter($conn, (int)$_SESSION['analyst_id'])) {
        requireModuleAccessJson('forms');
    }
    $data = json_decode(file_get_contents('php://input'), true) ?: [];
    $submissionId = (int)($data['submission_id'] ?? 0);
    if ($submissionId <= 0) throw new Exception('submission_id is required');
    catalogueApprovalMove($conn, (int)$_SESSION['analyst_id'], $submissionId, [
        'team_id'             => $data['team_id'] ?? null,
        'approvals_required'  => $data['approvals_required'] ?? null,
        'rejections_required' => $data['rejections_required'] ?? null,
        'approver_id'         => $data['approver_id'] ?? null,
    ], (string)($data['reason'] ?? ''));
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
