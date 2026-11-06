# messagingTelegramPlaceholderEmail()

The placeholder's synthetic address - MUST match `getOrCreateChannelUser()`.

```php
function messagingTelegramPlaceholderEmail(string $chatId): string
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 540.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$chatId` | `string` |  | the Telegram chat id |

**Returns:** `string`

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramIsPlaceholder()](/code/functions/messagingTelegramIsPlaceholder.md), [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)

