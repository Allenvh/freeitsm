# webchatLoadConversation()

Load a conversation by its token, scoped to the widget's channel (so a token issued for one widget can't be replayed against another). Null if not found.

```php
function webchatLoadConversation(PDO $conn, string $token, int $channelId): ?array
```

Defined in [includes/webchat/public.php](/code/files/includes/webchat/public.php.md), line 137.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$token` | `string` |  | the conversation token the widget holds - untrusted; '' gives null |
| `$channelId` | `int` |  | the widget's messaging_channels id the conversation must belong to |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 144 | `webchatLoadConversation` | read [webchat_conversations](/code/tables/webchat_conversations.md) |  |

## Called from

- [api/webchat/escalate.php](/code/files/api/webchat/escalate.php.md)
- [api/webchat/poll.php](/code/files/api/webchat/poll.php.md)
- [api/webchat/send.php](/code/files/api/webchat/send.php.md)

