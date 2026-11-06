# warRoomCanAccessChannel()

May this analyst read this channel?

⚠️ Enforced HERE, server-side, on every read and every write — never by only
rendering a shorter channel list. Anything unexpected fails CLOSED.

```php
function warRoomCanAccessChannel(PDO $conn, int $analystId, int $channelId): bool
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 242.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 252 | `warRoomCanAccessChannel` | read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Calls

[warRoomChannel()](/code/functions/warRoomChannel.md), [warRoomIsMember()](/code/functions/warRoomIsMember.md)

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)
- [api/war-room/poll.php](/code/files/api/war-room/poll.php.md)
- [api/war-room/search.php](/code/files/api/war-room/search.php.md)
- [api/war-room/sitrep.php](/code/files/api/war-room/sitrep.php.md)
- [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomCanPostChannel()](/code/functions/warRoomCanPostChannel.md), [warRoomDeleteMessage()](/code/functions/warRoomDeleteMessage.md), [warRoomAttachmentFor()](/code/functions/warRoomAttachmentFor.md)

