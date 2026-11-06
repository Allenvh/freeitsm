<?php
/**
 * API Endpoint: "if an email arrived from this address, which template would go back?"
 * POST { event_trigger, email } -> { template_id, template_name, reason, explanation }
 *
 * WHY A SIMULATOR EXISTS AT ALL
 * -----------------------------
 * Sender rules (discussion #80) are the first thing in FreeITSM where an
 * administrator's configuration decides whether somebody gets an email or silence,
 * and where being wrong produces no error — just a person who never heard back.
 * Reading the rules off the screen and working out the answer in your head is
 * exactly the step that goes wrong, so this does it with the same code that will
 * do it for real: templateSelectForRecipient(), not a second implementation of the
 * matching that could drift from it.
 *
 * It answers with the REASON as well as the winner, because "Standard reply" tells
 * you what happens and not whether it happens for the reason you intended.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/rbac.php';
require_once '../../includes/template_email.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}

requireModuleAccessJson('tickets');
requireCapabilityJson(Cap::TICKETS_EMAIL_TEMPLATES);

try {
    $data  = json_decode(file_get_contents('php://input'), true);
    $event = trim((string)($data['event_trigger'] ?? ''));
    $email = strtolower(trim((string)($data['email'] ?? '')));

    if ($event === '') {
        throw new Exception('Choose an event to test.');
    }
    if ($email === '' || !str_contains($email, '@')) {
        throw new Exception('Enter a full email address, including the @.');
    }

    $conn   = connectToDatabase();
    $choice = templateSelectForRecipient($conn, $event, $email);

    echo json_encode([
        'success'       => true,
        'template_id'   => $choice['template'] ? (int)$choice['template']['id'] : null,
        'template_name' => $choice['template'] ? $choice['template']['name'] : null,
        'reason'        => $choice['reason'],
        'matched_value' => $choice['matched']['match_value'] ?? null,
        'matched_type'  => $choice['matched']['match_type'] ?? null,
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
