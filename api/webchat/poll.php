<?php
/**
 * PUBLIC endpoint: fetch new messages in a conversation since a given id. Returns both
 * directions (so a reload rebuilds the full transcript) — inbound tagged 'visitor',
 * analyst replies tagged 'agent'. Needs the conversation token. The widget calls this
 * on a short interval; it's the delivery mechanism for analyst replies (v1 = polling).
 */
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/webchat/public.php';

webchatHandlePreflight();

$conn = connectToDatabase();

$key   = trim((string) ($_GET['key'] ?? ''));
$token = trim((string) ($_GET['token'] ?? ''));
$after = (int) ($_GET['after'] ?? 0);

$widget = webchatPublicResolve($conn, $key);

$conv = webchatLoadConversation($conn, $token, (int) $widget['channel_id']);
if (!$conv) {
    webchatFail('Chat session not found', 404);
}

$messages = [];
$lastId   = $after;
$closed   = false;
$ticketId = (int) ($conv['ticket_id'] ?? 0);
$aiEnabled = !empty($widget['ai_enabled']);

if ($aiEnabled) {
    // AI widget: webchat_messages is the visitor's transcript. Pull any new analyst
    // replies (Outbound emails) into it first, then read the transcript. The `after`
    // cursor is a webchat_messages id, not an emails id, in this mode.
    webchatMirrorAgentReplies($conn, $conv);
    $botName = (string) ($widget['name'] ?? 'Assistant');
    foreach (webchatGetMessages($conn, (int) $conv['id'], $after) as $m) {
        $sender = (string) $m['sender'];
        $messages[] = [
            'id'   => (int) $m['id'],
            'from' => $sender === 'visitor' ? 'visitor' : 'agent',
            'kind' => $sender, // visitor|ai|agent|system — lets the widget style it
            'name' => ($sender === 'ai' || $sender === 'agent') ? $botName : '',
            'body' => (string) $m['body'],
            'at'   => $m['created_datetime'],
        ];
        $lastId = (int) $m['id'];
    }
} elseif ($ticketId > 0) {
    $st = $conn->prepare(
        "SELECT id, direction, from_name, body_content, received_datetime
         FROM emails
         WHERE ticket_id = ? AND channel = 'webchat' AND id > ?
         ORDER BY id ASC LIMIT 100"
    );
    $st->execute([$ticketId, $after]);
    foreach ($st->fetchAll(PDO::FETCH_ASSOC) as $m) {
        $messages[] = [
            'id'   => (int) $m['id'],
            'from' => $m['direction'] === 'Outbound' ? 'agent' : 'visitor',
            'name' => (string) ($m['from_name'] ?? ''),
            'body' => (string) $m['body_content'],
            'at'   => $m['received_datetime'],
        ];
        $lastId = (int) $m['id'];
    }
}

// Let the widget show a "conversation closed" state when the ticket is resolved.
if ($ticketId > 0) {
    try {
        $cs = $conn->prepare(
            "SELECT ts.is_closed FROM tickets t
             JOIN ticket_statuses ts ON ts.id = t.status_id
             WHERE t.id = ? LIMIT 1"
        );
        $cs->execute([$ticketId]);
        $closed = (bool) $cs->fetchColumn();
    } catch (Exception $e) { /* leave open */ }
}

echo json_encode([
    'success'  => true,
    'messages' => $messages,
    'last_id'  => $lastId,
    'closed'   => $closed,
]);
