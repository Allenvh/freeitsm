# warRoomChannel()

One channel row, or null. Raw — callers must still check access.

```php
function warRoomChannel(PDO $conn, int $channelId): ?array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 217.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 220 | `warRoomChannel` | read [warroom_channel_members](/code/tables/warroom_channel_members.md), read [analysts](/code/tables/analysts.md), read [warroom_channels](/code/tables/warroom_channels.md), read [teams](/code/tables/teams.md) |  |

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)
- [includes/warbot/warbot.php](/code/files/includes/warbot/warbot.php.md) - in [warbotAnswer()](/code/functions/warbotAnswer.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md), [warRoomCanPostChannel()](/code/functions/warRoomCanPostChannel.md), [warRoomChannelAudience()](/code/functions/warRoomChannelAudience.md)

