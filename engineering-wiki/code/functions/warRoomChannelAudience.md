# warRoomChannelAudience()

Every analyst who can read this channel, as id => full name.

This is warRoomCanAccessChannel turned inside out, and the two must agree — a
name that resolves here but fails there would post a mention nobody can open.

```php
function warRoomChannelAudience(PDO $conn, int $channelId): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 791.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 801 | `warRoomChannelAudience` | read [analysts](/code/tables/analysts.md) |  |
| 806 | `warRoomChannelAudience` | read [analysts](/code/tables/analysts.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 814 | `warRoomChannelAudience` | read [analysts](/code/tables/analysts.md) |  |
| 820 | `warRoomChannelAudience` | read [analysts](/code/tables/analysts.md), read [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |

## Calls

[warRoomChannel()](/code/functions/warRoomChannel.md)

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomResolveMentions()](/code/functions/warRoomResolveMentions.md)

