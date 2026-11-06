# messagingBuildTranscript()

Build a plain-text transcript of a channel conversation (oldest first), capped to keep token usage predictable. Used by the AI summary / suggested-reply endpoints. Non-channel (email) rows are excluded.

```php
function messagingBuildTranscript(PDO $conn, int $ticketId): string
```

Defined in [includes/messaging/messaging.php](/code/files/includes/messaging/messaging.php.md), line 381.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 384 | `messagingBuildTranscript` | read [emails](/code/tables/emails.md) |  |

## Called from

- [api/messaging/ai_suggest_reply.php](/code/files/api/messaging/ai_suggest_reply.php.md)
- [api/messaging/ai_summary.php](/code/files/api/messaging/ai_summary.php.md)

