# warRoomOpenDm()

Find or create the DM between two analysts.

🔑 The pair is normalised into `dm_key` ("<lower>:<higher>") and that column is
UNIQUE. Without it, two people opening a DM with each other at the same moment
get two conversations and each sees half of it — which is precisely the failure
you would hit during an incident, when both are trying to reach the other.

```php
function warRoomOpenDm(PDO $conn, int $analystId, int $otherId): int
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 410.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$otherId` | `int` |  | the other analyst's id; must be active and not the caller |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 414 | `warRoomOpenDm` | read [analysts](/code/tables/analysts.md) |  |
| 420 | `warRoomOpenDm` | read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 427 | `warRoomOpenDm` | **insert** [warroom_channels](/code/tables/warroom_channels.md) (`kind`, `dm_key`, `is_private`, `created_by`, `created_datetime`) |  |

## Calls

[warRoomAddMembers()](/code/functions/warRoomAddMembers.md)

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)

