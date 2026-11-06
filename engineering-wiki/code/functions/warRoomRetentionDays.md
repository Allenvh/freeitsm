# warRoomRetentionDays()

Retention in days from settings. 0 (the default) means keep forever.

```php
function warRoomRetentionDays(PDO $conn): int
```

Defined in [includes/warroom.php](/code/files/includes/warroom.php.md), line 1218.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1221 | `warRoomRetentionDays` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/system/debug-tools/D008_war_room.php](/code/files/api/system/debug-tools/D008_war_room.php.md)
- [includes/warroom.php](/code/files/includes/warroom.php.md) - in [warRoomPrune()](/code/functions/warRoomPrune.md)
- [war-room/settings/index.php](/code/files/war-room/settings/index.php.md)

