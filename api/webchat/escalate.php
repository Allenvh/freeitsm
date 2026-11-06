<?php
/**
 * PUBLIC endpoint: the visitor escalates an AI (deflect-mode) conversation to a human.
 *
 *   mode = 'agent' — promote the chat to a live ticket (the transcript becomes the
 *                    opening message) and hand it to the team. Further messages behave
 *                    like any webchat ticket, and analyst replies stream back by polling.
 *   mode = 'email' — create a ticket with an AI summary as the body and the full chat
 *                    log attached as a .txt, then tell the visitor it'll be answered by
 *                    email. (The reply itself — outbound email — is the offline-email
 *                    piece; this endpoint only opens the ticket.)
 *
 * Idempotent: once the conversation has a ticket, this just reports success. Needs the
 * conversation token, and only acts when the widget's AI + the chosen route are enabled.
 */
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/webchat/public.php';
require_once '../../includes/webchat/ai.php';

webchatHandlePreflight();

$conn = connectToDatabase();
$data = json_decode(file_get_contents('php://input'), true) ?: [];

$key   = trim((string) ($data['key'] ?? ''));
$token = trim((string) ($data['token'] ?? ''));
$mode  = (($data['mode'] ?? '') === 'email') ? 'email' : 'agent';

$widget = webchatPublicResolve($conn, $key);

if (empty($widget['ai_enabled'])) {
    webchatFail('This chat does not support escalation', 400);
}
if ($mode === 'agent' && empty($widget['ai_offer_agent'])) {
    webchatFail('Speaking to a person is not available on this chat', 400);
}
if ($mode === 'email' && empty($widget['ai_offer_email'])) {
    webchatFail('Email follow-up is not available on this chat', 400);
}

$conv = webchatLoadConversation($conn, $token, (int) $widget['channel_id']);
if (!$conv) {
    webchatFail('Chat session not found — please refresh and start again', 404);
}

$channel = loadMessagingChannel($conn, (int) $widget['channel_id']);
if (!$channel) {
    webchatFail('This chat widget is no longer available', 410);
}

// Already escalated → nothing to do, report the existing ticket.
if (!empty($conv['ticket_id'])) {
    echo json_encode(['success' => true, 'ticket_id' => (int) $conv['ticket_id'], 'mode' => $mode]);
    exit;
}

$aiSummary = '';
if ($mode === 'email') {
    $aiSummary = webchatAiSummarise($conn, webchatTranscriptText($conn, (int) $conv['id']));
}

$ticketId = webchatPromoteToTicket($conn, $conv, $channel, $mode, $aiSummary ?: null);

// A persistent transcript note so the visitor sees the outcome (and it survives a reload).
$note = $mode === 'email'
    ? "Thanks — we've logged your enquiry and someone will reply to you by email."
    : "You're through to our team now — a person will reply here shortly.";
webchatAddMessage($conn, (int) $conv['id'], 'system', $note);

echo json_encode([
    'success'   => true,
    'ticket_id' => $ticketId,
    'mode'      => $mode,
    'message'   => $note,
]);
