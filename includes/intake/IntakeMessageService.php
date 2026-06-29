<?php
class IntakeMessageService {
    public static function ensureEmailChannel(PDO $conn, string $key, string $displayName, array $config = []): int {
        $stmt = $conn->prepare("INSERT IGNORE INTO intake_channels (channel_key, display_name, channel_type, config_json) VALUES (?, ?, 'email', ?)");
        $stmt->execute([$key, $displayName, json_encode($config)]);
        $sel = $conn->prepare("SELECT id FROM intake_channels WHERE channel_key = ?");
        $sel->execute([$key]);
        return (int)$sel->fetchColumn();
    }
    public static function record(PDO $conn, int $channelId, array $message): int {
        $stmt = $conn->prepare("INSERT IGNORE INTO intake_messages (channel_id, external_message_id, sender_identity, subject, body_text, body_html, status, metadata_json) VALUES (?, ?, ?, ?, ?, ?, 'new', ?)");
        $stmt->execute([$channelId, $message['id'], $message['from']['emailAddress']['address'] ?? null, $message['subject'] ?? null, strip_tags($message['body']['content'] ?? ''), $message['body']['content'] ?? null, json_encode($message['metadata'] ?? [])]);
        return (int)$conn->lastInsertId();
    }
    public static function markTicketCreated(PDO $conn, int $channelId, string $externalId, int $ticketId): void {
        $stmt = $conn->prepare("UPDATE intake_messages SET status = 'ticket_created', ticket_id = ?, processed_datetime = UTC_TIMESTAMP() WHERE channel_id = ? AND external_message_id = ?");
        $stmt->execute([$ticketId, $channelId, $externalId]);
    }
    // TODO: future AI triage hooks: summarize, classify, collect missing info, recommend KB, draft response, run approved L1 actions.
}
