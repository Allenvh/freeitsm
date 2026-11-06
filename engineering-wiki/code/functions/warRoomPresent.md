# warRoomPresent()

Who has polled recently, and whether they are in THIS channel or elsewhere in the war room. "Here" versus "around" is a genuinely useful distinction during an incident — it is the difference between nobody is reading this and everybody is in the other room.

```php
function warRoomPresent(PDO $conn, int $excludeAnalystId, int $channelId): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1171.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$excludeAnalystId` | `int` |  | the analyst asking, left out of both lists; 0 leaves nobody out |
| `$channelId` | `int` |  | the channel that counts as "here" |

**Returns:** array{here:string[],elsewhere:string[]}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1174 | `warRoomPresent` | read [warroom_presence](/code/tables/warroom_presence.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [api/war-room/poll.php](/code/files/api/war-room/poll.php.md)

