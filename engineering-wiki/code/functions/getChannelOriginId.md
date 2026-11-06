# getChannelOriginId()

The origin id for a channel type (e.g. the seeded 'WhatsApp' origin), or null.

```php
function getChannelOriginId(PDO $conn, string $channelType): ?int
```

Defined in [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), line 978.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelType` | `string` |  | the channel type, e.g. 'whatsapp' (looked up as the 'WhatsApp' origin) |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 982 | `getChannelOriginId` | read [ticket_origins](/code/tables/ticket_origins.md) |  |

## Called from

- [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md) - in [ingestInboundMessage()](/code/functions/ingestInboundMessage.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatOpenTicket()](/code/functions/webchatOpenTicket.md)

