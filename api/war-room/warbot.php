<?php
/**
 * API: War room — ask Warbot to answer a message.
 *
 * POST JSON { message_id: <int> }
 *
 * WHY THE CLIENT TRIGGERS THIS instead of send.php doing it inline: answering
 * takes a model round trip, sometimes several. Doing it inside send.php would
 * make every message in the room wait for it, including the ones not addressed to
 * Warbot. So the send returns immediately and the sender's browser asks for the
 * answer separately. There is no cron and no queue, which is the same reasoning
 * as retention pruning on write — an emergency tool must not depend on a
 * scheduled job somebody may never have set up.
 *
 * ⚠️ The consequence is that the trigger is UNTRUSTED and REPEATABLE: any analyst
 * in the channel could call it for any message id, and a retry or a double submit
 * could call it twice. So this endpoint re-derives everything from the message
 * itself — the channel, the asker, whether Warbot was addressed at all — and
 * refuses to answer the same message twice.
 */
session_start(['read_and_close' => true]);
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/i18n.php';
require_once '../../includes/warroom.php';
require_once '../../includes/warbot/warbot.php';

header('Content-Type: application/json');

requireModuleAccessJson('war-room');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'POST required']);
    exit;
}

try {
    $conn      = connectToDatabase();
    $analystId = (int) $_SESSION['analyst_id'];
    I18n::initFromSession();

    $input     = json_decode(file_get_contents('php://input'), true);
    $messageId = (int) (is_array($input) ? ($input['message_id'] ?? 0) : 0);

    $msg = warRoomMessageRow($conn, $messageId);
    if ($msg === null || $msg['deleted_datetime'] !== null) {
        echo json_encode(['success' => false, 'error' => 'no_message']);
        exit;
    }

    $channelId = (int) $msg['channel_id'];

    // The caller must be able to read the channel. Without this, an analyst could
    // make Warbot answer inside a private channel they are not in — and Warbot's
    // reply would then be visible to that channel's real members, written on their
    // behalf, sourced from a question they never asked.
    if (!warRoomCanAccessChannel($conn, $analystId, $channelId)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'error' => 'No access to that channel']);
        exit;
    }

    // Only answer a message that actually addressed Warbot, and only once.
    if (!warbotIsAddressed((string) $msg['body'])) {
        echo json_encode(['success' => false, 'error' => 'not_addressed']);
        exit;
    }
    if (warbotAlreadyAnswered($conn, $messageId)) {
        echo json_encode(['success' => true, 'already' => true]);
        exit;
    }

    // 🔑 Tools run as the message's AUTHOR, not as whoever poked this endpoint.
    // Otherwise an analyst could trigger somebody else's question and have it
    // answered with their own, wider permissions.
    $askerId = $msg['analyst_id'] !== null ? (int) $msg['analyst_id'] : $analystId;

    // Strip the @Warbot so the question reads as a question, and give the model a
    // little of what was said before it — an incident question is usually a
    // follow-on from the line above it.
    $question = trim(preg_replace('/@warbot\b/iu', '', (string) $msg['body']));

    $recent = [];
    foreach (warRoomMessages($conn, $channelId, 0, 12) as $m) {
        if ((int) $m['id'] === $messageId) continue;
        if (!empty($m['deleted'])) continue;
        // is_bot has to travel with the line: warbotAnswer() labels and trims
        // Warbot's own replies differently, and drops its old status notices.
        $recent[] = ['author' => $m['author'], 'body' => $m['body'], 'is_bot' => !empty($m['is_bot'])];
    }

    $answer = warbotAnswer($conn, $askerId, $channelId, $question, $recent);
    $id     = warbotPost($conn, $channelId, $answer['text'], $messageId);

    echo json_encode([
        'success'  => true,
        'id'       => $id,
        'degraded' => !empty($answer['degraded']),
        // Why it degraded, for whoever is debugging. Never posted into the room.
        'detail'   => $answer['detail'] ?? null,
    ]);
} catch (Throwable $e) {
    // Warbot failing must never look like the chat failing.
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Warbot could not answer']);
}
