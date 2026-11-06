# ticketAiSettings()

SETTINGS

Both features are OFF until somebody turns them on, which is not the usual
   FreeITSM default. Every other setting in this area changes how something
   already free is displayed; these two spend money with somebody else's API
   key, and a feature that starts billing on upgrade is not a good surprise.

```php
function ticketAiSettings(?PDO $conn = null): array
```

Defined in [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `?PDO` | `null` | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 | `ticketAiSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md)

## Called from

- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)
- [tickets/index.php](/code/files/tickets/index.php.md)

