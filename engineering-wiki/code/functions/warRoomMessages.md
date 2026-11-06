# warRoomMessages()

Messages in a channel, oldest first, each with its attachments.

`$sinceId` drives the poll: the page asks for everything newer than the last id
it holds, so a quiet channel costs one indexed lookup that returns nothing.
On the first load `$sinceId` is 0 and we return the tail rather than the lot.

A NULL analyst_id is a deleted account, rendered as "Former analyst" rather
than blank or "Unknown" so the reader can tell WHICH case it is.

```php
function warRoomMessages(PDO $conn, int $channelId, int $sinceId = 0, int $limit = 200): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 488.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id; the caller has already checked access |
| `$sinceId` | `int` | `0` | the newest message id the page holds; 0 for the first load (the latest `$limit`) |
| `$limit` | `int` | `200` | the most messages to return, 1-500 |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 507 | `warRoomMessages` | read [analysts](/code/tables/analysts.md) |  |
| 511 | `warRoomMessages` | read [warroom_messages](/code/tables/warroom_messages.md) |  |
| 519 | `warRoomMessages` | read [warroom_messages](/code/tables/warroom_messages.md) |  |
| 565 | `warRoomMessages` | read [warroom_attachments](/code/tables/warroom_attachments.md) |  |

## Calls

[attachmentServeRules()](/code/functions/attachmentServeRules.md), [t()](/code/functions/t.md)

## Called from

- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [api/war-room/poll.php](/code/files/api/war-room/poll.php.md)
- [api/war-room/warbot.php](/code/files/api/war-room/warbot.php.md)

