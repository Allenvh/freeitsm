# ticketDisplaySettings()

The display settings, with defaults, in one place.

Defaults chosen so an install that never opens the settings behaves sensibly
rather than surprisingly: collapsing ON (it is what was asked for), the newest
message always open (it is the one you came for), and the threshold generous
enough that ordinary human replies are never touched.

```php
function ticketDisplaySettings(?PDO $conn = null): array
```

Defined in [includes/ticket_display.php](/code/files/includes/ticket_display.php.md), line 46.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 76 | `ticketDisplaySettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [tickets/index.php](/code/files/tickets/index.php.md)

