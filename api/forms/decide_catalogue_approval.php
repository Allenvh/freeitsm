<?php
/**
 * API: approve or reject a pending catalogue request (#928).
 *
 * POST { submission_id, decision: 'approved'|'rejected', comment?, override? }
 *   -> { success, decision, ticket_id, ticket_number, progress? }
 *
 * Team approval (GH #220): the call is one member's vote, and decision comes back
 * 'recorded' (with progress) until enough members have decided. override: true is an
 * administrator deciding without the votes - comment is then the required reason.
 *
 * Approving raises the ticket the request becomes (correct portal tenancy/identity)
 * and stamps form_submissions.ticket_id; rejecting records the decision only. The
 * engine refuses if the caller is not the assigned approver (or an admin), or if the
 * request is not still pending. See includes/catalogue_approvals.php.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/catalogue_approvals.php';

header('Content-Type: application/json');
if (!isset($_SESSION['analyst_id'])) { echo json_encode(['success' => false, 'error' => 'Not authenticated']); exit; }

try {
    // Forms access, or named as an approver (#180 Part 3). Deciding itself is still
    // catalogueApprovalDecide()'s approver_id check - only your own requests.
    if (!catalogueApproverCanEnter(connectToDatabase(), (int)$_SESSION['analyst_id'])) {
        requireModuleAccessJson('forms');
    }
    $data         = json_decode(file_get_contents('php://input'), true) ?: [];
    $submissionId = (int)($data['submission_id'] ?? 0);
    $decision     = (string)($data['decision'] ?? '');
    $comment      = (string)($data['comment'] ?? '');

    if ($submissionId <= 0) throw new Exception('submission_id is required');

    $conn = connectToDatabase();
    $res  = !empty($data['override'])
        ? catalogueApprovalOverride($conn, (int)$_SESSION['analyst_id'], $submissionId, $decision, $comment)
        : catalogueApprovalDecide($conn, (int)$_SESSION['analyst_id'], $submissionId, $decision, $comment);
    echo json_encode(array_merge(['success' => true], $res));
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
