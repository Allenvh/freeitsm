<?php
/**
 * Send ONE of a ticket's attachments to an issue it is already linked to.
 *
 * The gap this fills: attachments only travelled at the moment of escalation,
 * so the screenshot that turns up *after* a developer asks "can you send me the
 * error?" — which is when it usually turns up — had nowhere to go.
 *
 * Deliberately one file, chosen by a person, rather than "push everything new
 * automatically". An attachment cannot be unsent, and somebody attaching a
 * screenshot to a ticket is usually replying to a colleague, not publishing to
 * the dev team. The decision stays with the analyst.
 *
 * Body: { attachment_id, link_id }
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/tenancy.php';
require_once '../../includes/integrations/integrations.php';

// Changes data: never on a GET, which a link on another site can send (csrf.php).
csrfRequireWriteMethod();

header('Content-Type: application/json');
requireModuleAccessJson('tickets');

$in = json_decode(file_get_contents('php://input'), true);
if (!is_array($in)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Bad request.']);
    exit;
}

$attachmentId = (int)($in['attachment_id'] ?? 0);
$linkId       = (int)($in['link_id'] ?? 0);

$conn = connectToDatabase();

if (!integrationsSchemaReady($conn)) {
    echo json_encode(['success' => false, 'error' => 'Integrations are not set up on this install yet.']);
    exit;
}
if ($attachmentId <= 0 || $linkId <= 0) {
    echo json_encode(['success' => false, 'error' => 'Nothing to send.']);
    exit;
}

// ── The link, and the ticket it belongs to ────────────────────────────────
$lq = $conn->prepare("SELECT * FROM integration_links WHERE id = ? AND entity_type = 'ticket'");
$lq->execute([$linkId]);
$link = $lq->fetch(PDO::FETCH_ASSOC);
if (!$link) {
    echo json_encode(['success' => false, 'error' => 'That linked issue no longer exists.']);
    exit;
}
$ticketId = (int)$link['entity_id'];

// ⚠️ Multi-company: the same check the ticket view uses. Without it, an
// attachment id plus a link id from another company's ticket would be enough to
// push that company's file into a tracker this analyst can reach.
if (function_exists('analystCanAccessTicket')
    && !analystCanAccessTicket($conn, (int)$_SESSION['analyst_id'], $ticketId)) {
    echo json_encode(['success' => false, 'error' => 'Ticket not found']);
    exit;
}

// ⚠️ The attachment must belong to THIS ticket. Trusting the id alone would let
// any attachment in the system be posted to any issue — the classic
// insecure-direct-object-reference, and here it ends with somebody else's file
// on a dev team's board.
$sendable = null;
foreach (integrationsTicketAttachments($conn, $ticketId, PHP_INT_MAX) as $a) {
    if ($a['id'] === $attachmentId) { $sendable = $a; break; }
}
if (!$sendable) {
    // Also covers inline images, which integrationsTicketAttachments() excludes.
    echo json_encode(['success' => false, 'error' => 'That file is not one of this ticket\'s attachments.']);
    exit;
}
if (!empty($sendable['skip_reason'])) {
    $why = $sendable['skip_reason'] === 'too_large'
        ? 'That file is larger than the 10MB limit.'
        : 'That file is no longer on disk.';
    echo json_encode(['success' => false, 'error' => $why]);
    exit;
}

$connection = integrationsLoadConnection($conn, (int)$link['connection_id']);
if (!$connection || empty($connection['is_active'])) {
    echo json_encode(['success' => false, 'error' => 'That tracker connection is switched off.']);
    exit;
}

// ── Send it ───────────────────────────────────────────────────────────────
// Note this ignores the connection's `send_attachments` setting on purpose:
// that governs what happens AUTOMATICALLY on escalation. This is an analyst
// deliberately choosing one file, which is a different question.
$res = integrationsSendAttachments($conn, $connection, $link, [$sendable]);

if ($res['sent'] !== 1) {
    echo json_encode([
        'success' => false,
        'error'   => $res['notes'] ? implode('; ', $res['notes']) : 'The tracker refused the file.',
    ]);
    exit;
}

// An audit trail an analyst can actually see. Sending a file out of the company
// is worth a line on the ticket — and it stops two people sending it twice.
try {
    $conn->prepare(
        "INSERT INTO ticket_notes (ticket_id, analyst_id, note_text, is_internal, created_datetime)
         VALUES (?, ?, ?, 1, UTC_TIMESTAMP())"
    )->execute([
        $ticketId,
        (int)$_SESSION['analyst_id'],
        'Sent "' . $sendable['filename'] . '" to ' . ($link['external_key'] ?: 'the linked issue') . '.',
    ]);
} catch (Exception $e) {
    // The file is already there; failing to note it must not report failure.
    error_log('send_attachment note: ' . $e->getMessage());
}

echo json_encode([
    'success'  => true,
    'filename' => $sendable['filename'],
    'issue'    => $link['external_key'] ?: '',
]);
