# warRoomSetArchived()

Archive or restore a custom channel. Team and all-hands channels cannot be archived — they are derived, so the way to remove one is to remove the team.

```php
function warRoomSetArchived(PDO $conn, int $channelId, bool $archived): bool
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 366.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id; only custom and DM channels change |
| `$archived` | `bool` |  | true to archive, false to restore |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 369 | `warRoomSetArchived` | **update** [warroom_channels](/code/tables/warroom_channels.md) (`archived_datetime`) |  |

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)

