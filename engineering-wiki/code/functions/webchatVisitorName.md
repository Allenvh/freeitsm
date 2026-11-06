# webchatVisitorName()

The visitor's display name for a conversation (name → email → generic).

```php
function webchatVisitorName(array $conversation): string
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 204.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conversation` | `array` |  | a webchat_conversations row (visitor_name, visitor_email) |

**Returns:** `string`

## Called from

- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatInsertInbound()](/code/functions/webchatInsertInbound.md), [webchatIngestMessage()](/code/functions/webchatIngestMessage.md), [webchatPromoteToTicket()](/code/functions/webchatPromoteToTicket.md)

