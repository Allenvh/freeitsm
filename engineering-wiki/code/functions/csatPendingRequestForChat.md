# csatPendingRequestForChat()

The rating request a customer's bare digit answers, or null - in which case the digit is an ordinary message. All of these must hold: - the request was sent IN THIS CHAT (channel_id + channel_from); - it is unanswered and at most CSAT_CHANNEL_REPLY_WINDOW_DAYS old; - the customer has written nothing else in this chat since it was sent.

TRAP: a bare digit is usually NOT a rating. "Which floor?" "3" is an answer
  to the analyst, and a "2" next month starts a new conversation - loosen any
  of the three conditions and real messages vanish into a survey, with no
  error anywhere. tests/messaging-teams-mattermost.php checks each one.

```php
function csatPendingRequestForChat(PDO $conn, int $channelId, string $from): ?int
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 221.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | messaging_channels.id the digit arrived on |
| `$from` | `string` |  | the sender's id on that channel (from_address) |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 224 | `csatPendingRequestForChat` | read [ticket_csat_responses](/code/tables/ticket_csat_responses.md) |  |
| 227 | `csatPendingRequestForChat` | read [emails](/code/tables/emails.md) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)

