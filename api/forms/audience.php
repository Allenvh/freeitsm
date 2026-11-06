<?php
/**
 * Who may request a form from the self-service catalogue (GH #145).
 *
 *   GET  ?form_id=N        — the groups that exist, and which are selected
 *   POST {form_id, group_ids} — replace the selection ([] = everyone)
 *
 * 🔑 The groups are PEOPLE groups (Tickets → Users → Groups). They are the only
 * one of the three group concepts in this product that holds portal users as
 * well as analysts, which is exactly why they are the right principal here — a
 * team holds analysts, and analysts do not use the catalogue.
 *
 * ⚠️ This is an ANALYST endpoint. It decides what CUSTOMERS may see, but the
 * deciding is administration, so it is gated on the Forms module like every
 * other form setting. The enforcement lives in the portal's own queries.
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

try {
    $conn = connectToDatabase();

    if (!FormsService::audiencesAvailable($conn)) {
        echo json_encode([
            'success'   => false,
            'code'      => 'not_ready',
            'error'     => 'Restricting a form needs Database Verification to be run first.',
        ]);
        exit;
    }

    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
        // Who may request a form is part of designing it (#180).
        require_once '../../includes/rbac.php';
        requireModuleWriteJson('forms');
        requireActionJson(Cap::FORMS_DESIGN);
        $body   = json_decode(file_get_contents('php://input'), true);
        $formId = (int)($body['form_id'] ?? 0);
        $ids    = $body['group_ids'] ?? [];
        if (!is_array($ids)) $ids = [];
        if ($formId <= 0) { echo json_encode(['success' => false, 'error' => 'Missing form ID']); exit; }

        FormsService::setFormAudiences($conn, $formId, $ids);
        echo json_encode(['success' => true, 'restricted' => count($ids) > 0]);
        exit;
    }

    $formId = (int)($_GET['form_id'] ?? 0);
    if ($formId <= 0) { echo json_encode(['success' => false, 'error' => 'Missing form ID']); exit; }

    /* Every group that could be chosen, with how many CUSTOMERS are in it —
       a group of analysts only would restrict the form to nobody, and the
       number is the cheapest way to make that visible before it is saved. */
    $groups = $conn->query(
        "SELECT g.id, g.name, g.description,
                (SELECT COUNT(*) FROM knowledge_user_group_members m
                  WHERE m.group_id = g.id AND m.member_type = 'user'
                    AND (m.expires_at IS NULL OR m.expires_at > UTC_TIMESTAMP())) AS customer_count
           FROM knowledge_user_groups g
          WHERE g.is_active = 1
          ORDER BY g.name"
    )->fetchAll(PDO::FETCH_ASSOC);

    foreach ($groups as &$g) {
        $g['id'] = (int)$g['id'];
        $g['customer_count'] = (int)$g['customer_count'];
    }
    unset($g);

    echo json_encode([
        'success'  => true,
        'groups'   => $groups,
        'selected' => FormsService::formAudiences($conn, $formId),
    ]);

} catch (ServiceError $e) {
    http_response_code(serviceErrorHttpStatus($e->kind));
    echo json_encode(['success' => false, 'error' => $e->getMessage(), 'code' => $e->errorCode]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Could not load that setting.']);
}
