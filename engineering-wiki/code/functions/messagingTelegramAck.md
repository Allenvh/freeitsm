# messagingTelegramAck()

Best-effort reply to the chat. A failed send must never break ingest.

Every outcome sends the SAME neutral text: telling a stranger "found your
account" would let anyone holding a phone confirm who is in this install.

```php
function messagingTelegramAck(array $channel, string $chatId, string $text): void
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 872.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$channel` | `array` |  | the Telegram messaging_channels row, decrypted; other channel types send nothing |
| `$chatId` | `string` |  | the Telegram chat id |
| `$text` | `string` |  | the message to send |

**Returns:** `void`

## Calls

[messagingProvider()](/code/functions/messagingProvider.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingTelegramLinkPhone()](/code/functions/messagingTelegramLinkPhone.md)

