# warRoomMarkRead()

Remember how far this analyst has read, so the channel list can show what is new. Only ever moves forward: an out-of-order poll must not un-read a channel.

```php
function warRoomMarkRead(PDO $conn, int $analystId, int $channelId, int $messageId): void
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1200.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id |
| `$messageId` | `int` |  | the newest message id read; 0 or less does nothing |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1204 | `warRoomMarkRead` | **insert** [warroom_reads](/code/tables/warroom_reads.md) (`analyst_id`, `channel_id`, `last_read_message_id`, `updated_datetime`) |  |

## Called from

- [api/war-room/poll.php](/code/files/api/war-room/poll.php.md)

