# webchatMirrorAgentReplies()

Mirror any new analyst replies (Outbound webchat emails on the conversation's ticket) into the transcript, so an AI-enabled widget — which reads webchat_messages, not the emails table — shows them to the visitor. Deduped by source_email_id, so AI answers already recorded with their source email (and previously mirrored replies) are skipped.

```php
function webchatMirrorAgentReplies(PDO $conn, array $conversation): void
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 496.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$conversation` | `array` |  | the webchat_conversations row; nothing happens until it has a ticket_id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 504 | `webchatMirrorAgentReplies` | read [webchat_messages](/code/tables/webchat_messages.md) |  |
| 509 | `webchatMirrorAgentReplies` | read [emails](/code/tables/emails.md) |  |

## Calls

[webchatAddMessage()](/code/functions/webchatAddMessage.md)

## Called from

- [api/webchat/poll.php](/code/files/api/webchat/poll.php.md)

