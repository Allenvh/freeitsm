# warRoomDirectory()

Analysts this person could start a DM with. Active accounts only — offering to message somebody who left is a dead end you only discover after typing.

```php
function warRoomDirectory(PDO $conn, int $analystId): array
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 448.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 451 | `warRoomDirectory` | read [warroom_presence](/code/tables/warroom_presence.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/war-room/channels.php](/code/files/api/war-room/channels.php.md)
- [war-room/index.php](/code/files/war-room/index.php.md)

