<?php
/**
 * A form draft, for a SELF-SERVICE customer.
 *
 *   GET    ?form_id=N        — this customer's draft of that form, or null
 *   POST   {form_id, answers} — save (overwriting their previous one)
 *   DELETE ?form_id=N        — throw it away
 *
 * 🔑 A DELIBERATELY SEPARATE ENDPOINT from the analyst one, not a shared file
 * with a branch. The two differ in the only thing that matters here — who the
 * owner is and which forms they may touch — and a single endpoint deciding that
 * from whichever session happens to be set is exactly how a customer ends up
 * reading an analyst's draft.
 *
 * 🔴 A customer may only draft a form they could actually fill in: in the
 * catalogue, active, and the current version. The gate is in the QUERY, so a
 * form that is not offered is indistinguishable from one that does not exist.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/forms.php';

header('Content-Type: application/json');

if (!isset($_SESSION['ss_user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

$ownerId = (int)$_SESSION['ss_user_id'];
$method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$formId  = (int)($_GET['form_id'] ?? 0);

try {
    $conn = connectToDatabase();
    // GH #217: nothing from a module switched off for the install.
    requireModuleOnJson($conn, 'forms');

    if ($method === 'POST') {
        $body    = json_decode(file_get_contents('php://input'), true);
        $formId  = (int)($body['form_id'] ?? 0);
        $answers = $body['answers'] ?? [];
        if (!is_array($answers)) $answers = [];
    }

    if ($formId <= 0) {
        echo json_encode(['success' => false, 'error' => 'Missing form ID']);
        exit;
    }

    /* The same visibility gate get_catalogue_form.php uses. Checked on every
       verb, including DELETE — a customer should not be able to probe which
       form ids exist by watching which deletes succeed. */
    $vis = $conn->prepare(
        "SELECT f.id FROM forms f
          WHERE f.id = :fid AND f.is_portal_visible = 1 AND f.is_active = 1
            AND NOT EXISTS (SELECT 1 FROM forms ch WHERE ch.parent_form_id = f.id)
            AND " . FormsService::portalAudienceSql($conn, 'f')
    );
    $vis->execute([':fid' => $formId, ':audUser' => $ownerId]);
    if (!$vis->fetchColumn()) {
        echo json_encode(['success' => false, 'error' => 'Form not found']);
        exit;
    }

    if ($method === 'GET') {
        echo json_encode(['success' => true, 'draft' => FormsService::loadDraft($conn, $formId, 'portal', $ownerId)]);
        exit;
    }
    if ($method === 'DELETE') {
        FormsService::deleteDraft($conn, $formId, 'portal', $ownerId);
        echo json_encode(['success' => true]);
        exit;
    }
    if ($method === 'POST') {
        echo json_encode(['success' => true] + FormsService::saveDraft($conn, $formId, 'portal', $ownerId, $answers));
        exit;
    }

    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);

} catch (ServiceError $e) {
    http_response_code(serviceErrorHttpStatus($e->kind));
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not save that draft.']);
}
