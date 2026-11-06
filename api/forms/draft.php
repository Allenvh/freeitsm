<?php
/**
 * A form draft, for the ANALYST side.
 *
 *   GET    ?form_id=N   — the current user's draft of that form, or null
 *   POST   {form_id, answers}   — save (overwriting their previous one)
 *   DELETE ?form_id=N   — throw it away
 *
 * 🔑 THE OWNER IS THE SESSION, NEVER THE REQUEST. A draft is personal, so who
 * it belongs to is taken from $_SESSION and a posted owner id is ignored
 * entirely — otherwise anyone could read or overwrite anybody else's.
 *
 * 🔴 Nothing here validates the answers against the form. Not being finished is
 * the whole point; validation happens on submit. What IS enforced, in the
 * service, is that the keys are field ids belonging to that form, so a draft
 * cannot be used as somewhere to park arbitrary data.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/services/forms.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('forms');
if (in_array($_SERVER['REQUEST_METHOD'] ?? 'GET', ['POST', 'DELETE'], true)) {
    requireModuleWriteJson('forms');   // Read only access (#180) fills in nothing
}

$ownerId = (int)$_SESSION['analyst_id'];
$method  = $_SERVER['REQUEST_METHOD'] ?? 'GET';

try {
    $conn = connectToDatabase();

    if ($method === 'GET') {
        $formId = (int)($_GET['form_id'] ?? 0);
        if ($formId <= 0) { echo json_encode(['success' => false, 'error' => 'Missing form ID']); exit; }
        echo json_encode(['success' => true, 'draft' => FormsService::loadDraft($conn, $formId, 'analyst', $ownerId)]);
        exit;
    }

    if ($method === 'DELETE') {
        $formId = (int)($_GET['form_id'] ?? 0);
        if ($formId <= 0) { echo json_encode(['success' => false, 'error' => 'Missing form ID']); exit; }
        FormsService::deleteDraft($conn, $formId, 'analyst', $ownerId);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($method === 'POST') {
        $body    = json_decode(file_get_contents('php://input'), true);
        $formId  = (int)($body['form_id'] ?? 0);
        $answers = $body['answers'] ?? [];
        if (!is_array($answers)) $answers = [];
        echo json_encode(['success' => true] + FormsService::saveDraft($conn, $formId, 'analyst', $ownerId, $answers));
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
