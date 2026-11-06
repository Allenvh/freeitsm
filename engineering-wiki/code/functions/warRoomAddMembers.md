# warRoomAddMembers()

Add analysts to a channel. Idempotent — re-inviting somebody is not an error.

```php
function warRoomAddMembers(PDO $conn, int $channelId, array $analystIds): void
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 343.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id |
| `$analystIds` | `array` |  | analysts.id values to add; ids of 0 or less and inactive analysts are skipped |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 346 | `warRoomAddMembers` | **insert** [warroom_channel_members](/code/tables/warroom_channel_members.md) (`channel_id`, `analyst_id`, `created_datetime`), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomCreateChannel()](/code/functions/warRoomCreateChannel.md), [warRoomOpenDm()](/code/functions/warRoomOpenDm.md)

