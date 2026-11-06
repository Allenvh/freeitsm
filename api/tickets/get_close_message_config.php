<?php
/**
 * API Endpoint: should closing a ticket offer a one-off message? (#142)
 *
 * GET -> { success: true, enabled: bool }
 *
 * 🔑 The prompt appears ONLY when an operator has actually put
 * [ticket_closed_message] into an active "Ticket closed" template. Two reasons,
 * and both are about not wasting the analyst's attention:
 *
 *  - Without the placeholder, anything typed is silently discarded. The person
 *    closing the ticket has no way of knowing whether the template was ever
 *    customised, so the safe thing is not to ask.
 *  - Every install that has not configured this would otherwise gain an extra
 *    click on every single close, forever.
 *
 * ⚠️ Deliberately NOT recipient-aware. templateSelectForRecipient() picks a
 * template from the requester's address, so strictly the right answer varies by
 * ticket. But this runs before the analyst has committed to closing, the
 * difference only matters on installs using per-sender template rules, and the
 * failure it would cause (a prompt that turns out not to be used) is far milder
 * than its opposite (no prompt where one was wanted). Any ACTIVE template for
 * the event carrying the code is enough to offer it.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';

header('Content-Type: application/json');

if (!isset($_SESSION['analyst_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not authenticated']);
    exit;
}
requireModuleAccessJson('tickets');

try {
    $conn = connectToDatabase();
    $stmt = $conn->prepare(
        "SELECT COUNT(*) FROM ticket_email_templates
          WHERE event_trigger = 'ticket_closed'
            AND is_active = 1
            AND (body_template LIKE '%[ticket_closed_message]%'
              OR subject_template LIKE '%[ticket_closed_message]%')"
    );
    $stmt->execute();
    echo json_encode(['success' => true, 'enabled' => ((int)$stmt->fetchColumn()) > 0]);
} catch (Exception $e) {
    // A diagnostic query must never stop somebody closing a ticket: fail closed
    // (no prompt) rather than blocking the close behind a broken lookup.
    error_log('get_close_message_config: ' . $e->getMessage());
    echo json_encode(['success' => true, 'enabled' => false]);
}
