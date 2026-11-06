# warRoomEnsureChannels()

Make sure the channels this analyst is entitled to actually exist as rows.

Called at list time rather than by an installer or a cron, so a team created
ten minutes ago has a channel the first time anybody looks — nothing to set
up in advance and nothing to run afterwards. Both inserts are idempotent:
the all-hands row is guarded by its unique kind, and team rows by the unique
on team_id, so concurrent pollers race harmlessly.

```php
function warRoomEnsureChannels(PDO $conn, int $analystId): void
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 82.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 88 | `warRoomEnsureChannels` | **insert** [warroom_channels](/code/tables/warroom_channels.md) (`kind`, `name`, `created_datetime`) |  |
| 89 | `warRoomEnsureChannels` | read [warroom_channels](/code/tables/warroom_channels.md) |  |
| 95 | `warRoomEnsureChannels` | **insert** [warroom_channels](/code/tables/warroom_channels.md) (`kind`, `team_id`, `created_datetime`) |  |
| 96 | `warRoomEnsureChannels` | read [teams](/code/tables/teams.md), read [analyst_teams](/code/tables/analyst_teams.md), read [warroom_channels](/code/tables/warroom_channels.md) |  |

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomChannelList()](/code/functions/warRoomChannelList.md)

