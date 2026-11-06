<?php
/**
 * PUBLIC endpoint: start a web chat conversation. Returns a per-conversation token the
 * browser keeps and presents on every subsequent send/poll. No ticket is created yet —
 * that happens on the first actual message (send.php), so an abandoned "opened the
 * widget but said nothing" never litters the inbox.
 */
require_once '../../config.php';
require_once '../../includes/functions.php';
require_once '../../includes/webchat/public.php';

webchatHandlePreflight();

$conn = connectToDatabase();
$data = json_decode(file_get_contents('php://input'), true) ?: [];

$key    = trim((string) ($data['key'] ?? ''));
$widget = webchatPublicResolve($conn, $key);

$name  = trim((string) ($data['name'] ?? ''));
$email = trim((string) ($data['email'] ?? ''));

// Pre-chat identity gate (when the widget asks for it).
if (!empty($widget['require_email'])) {
    if ($name === '' || $email === '') {
        webchatFail('Please enter your name and email to start the chat');
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        webchatFail('Please enter a valid email address');
    }
}
$name  = mb_substr($name, 0, 150);
$email = mb_substr($email, 0, 255);

$ip = webchatClientIp();
webchatRateLimitStart($conn, $ip);

$token = webchatGenerateToken();
$conn->prepare(
    "INSERT INTO webchat_conversations
        (channel_id, token, visitor_name, visitor_email, visitor_ip,
         created_datetime, last_activity_datetime)
     VALUES (?, ?, ?, ?, ?, UTC_TIMESTAMP(), UTC_TIMESTAMP())"
)->execute([
    (int) $widget['channel_id'], $token,
    $name ?: null, $email ?: null, $ip ?: null,
]);

echo json_encode(['success' => true, 'token' => $token]);
