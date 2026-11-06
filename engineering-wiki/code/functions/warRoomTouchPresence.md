# warRoomTouchPresence()

Record that this analyst is here, and return who else is.

The poll that fetches messages calls this, so presence costs no extra request
and there is no separate heartbeat to keep alive. The UPSERT onto the UNIQUE
analyst_id is what stops the table growing — one row per person, forever.

```php
function warRoomTouchPresence(PDO $conn, int $analystId, int $channelId): void
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1149.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the channel the analyst has open |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1152 | `warRoomTouchPresence` | **insert** [warroom_presence](/code/tables/warroom_presence.md) (`analyst_id`, `channel_id`, `last_seen`) |  |

## Called from

- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [api/war-room/poll.php](/code/files/api/war-room/poll.php.md)

