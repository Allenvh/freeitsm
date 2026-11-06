# messagingRecordCsatReply()

Record a CSAT rating that came in from a customer's chat, then thank them. A rating only counts if its request was sent to THIS customer on THIS channel and has not been answered yet, so a replayed or forged button press changes nothing. Never creates or touches a ticket.

```php
function messagingRecordCsatReply(
    PDO $conn,
    array $channel,
    string $chatId,
    string $replyAddress,
    array $reply
): array
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 812.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the messaging_channels row, decrypted |
| `$chatId` | `string` |  | the normalised sender id the rating came from |
| `$replyAddress` | `string` |  | where the thanks goes - see `messagingReplyAddress()` |
| `$reply` | `array` |  | response_id, rating, button (true for a button press), and for Telegram callback_id, message_id, message_text |

**Returns:** `array`

## Calls

[csatChannelLocale()](/code/functions/csatChannelLocale.md), [csatRecordRating()](/code/functions/csatRecordRating.md), [csatResponseBelongsToChat()](/code/functions/csatResponseBelongsToChat.md), [csatStoredRating()](/code/functions/csatStoredRating.md), [messagingProvider()](/code/functions/messagingProvider.md)

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)

