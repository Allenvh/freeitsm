# webchatTranscriptText()

The full chat transcript as plain text — "Visitor: …" / "Assistant: …" / "Agent: …". Used as the opening message when a deflect conversation is escalated to a ticket, and as the .txt attachment on an email escalation.

```php
function webchatTranscriptText(PDO $conn, int $conversationId): string
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 477.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$conversationId` | `int` |  | the webchat_conversations id |

**Returns:** `string`

## Calls

[webchatGetMessages()](/code/functions/webchatGetMessages.md)

## Called from

- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md)

