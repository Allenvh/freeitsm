# webchatLoadByChannel()

Load a widget row joined to its channel, keyed by channel id. Null if missing.

```php
function webchatLoadByChannel(PDO $conn, int $channelId): ?array
```

Defined in [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md), line 86.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the messaging_channels id of the web chat channel |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 | `webchatLoadByChannel` | read [webchat_widgets](/code/tables/webchat_widgets.md), read [messaging_channels](/code/tables/messaging_channels.md) |  |

*Nothing calls this function by name.*

