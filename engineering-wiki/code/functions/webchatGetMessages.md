# webchatGetMessages()

Read a conversation's transcript messages after `$afterId` (ascending).

```php
function webchatGetMessages(PDO $conn, int $conversationId, int $afterId = 0): array
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 458.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$conversationId` | `int` |  | the webchat_conversations id |
| `$afterId` | `int` | `0` | the last webchat_messages id the caller holds; 0 for the start (at most 200 returned) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 461 | `webchatGetMessages` | read [webchat_messages](/code/tables/webchat_messages.md) |  |

## Called from

- [api/webchat/poll.php](/code/files/api/webchat/poll.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatTranscriptText()](/code/functions/webchatTranscriptText.md)

