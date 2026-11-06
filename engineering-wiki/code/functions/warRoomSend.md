# warRoomSend()

Post a message. Returns the new id, or 0 if the body was empty.

```php
function warRoomSend(PDO $conn, int $analystId, int $channelId, string $body): int
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 679.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id; the caller has already checked it may post there |
| `$body` | `string` |  | the message text as typed; blank posts nothing; cut to WARROOM_MAX_BODY |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 686 | `warRoomSend` | **insert** [warroom_messages](/code/tables/warroom_messages.md) (`channel_id`, `analyst_id`, `body`, `created_datetime`) |  |

## Calls

[warRoomPrune()](/code/functions/warRoomPrune.md), [warRoomResolveMentions()](/code/functions/warRoomResolveMentions.md), [warRoomStoreMentions()](/code/functions/warRoomStoreMentions.md)

## Called from

- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [api/war-room/send.php](/code/files/api/war-room/send.php.md)

