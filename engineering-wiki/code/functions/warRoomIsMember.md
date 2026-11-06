# warRoomIsMember()

True when the analyst is a listed member of the channel - what decides access to a private channel or a DM.

```php
function warRoomIsMember(PDO $conn, int $analystId, int $channelId): bool
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 272.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$channelId` | `int` |  | the warroom_channels id |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 274 | `warRoomIsMember` | read [warroom_channel_members](/code/tables/warroom_channel_members.md) |  |

## Called from

- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomCanAccessChannel()](/code/functions/warRoomCanAccessChannel.md)

