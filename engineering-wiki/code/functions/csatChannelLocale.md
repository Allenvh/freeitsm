# csatChannelLocale()

The language to write a customer-facing rating message in. Telegram has the customer's own language_code (stored on the identity link); other channels do not report one, so they get English.

```php
function csatChannelLocale(PDO $conn, array $channel, string $from): string
```

Defined in [includes/csat.php](/code/files/includes/csat.php.md), line 164.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channel` | `array` |  | the messaging_channels row as `loadMessagingChannel()` returns it |
| `$from` | `string` |  | the customer's id on that channel (the inbound message's from_address) |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 169 | `csatChannelLocale` | read [messaging_identity_links](/code/tables/messaging_identity_links.md) |  |

## Called from

- [includes/csat.php](/code/files/includes/csat.php.md) - in [csatSendInChannel()](/code/functions/csatSendInChannel.md)
- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [messagingRecordCsatReply()](/code/functions/messagingRecordCsatReply.md)

