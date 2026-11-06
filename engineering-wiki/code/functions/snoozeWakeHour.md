# snoozeWakeHour()

The hour "Tomorrow" and "Next week" land on — Tickets → Settings → General, default 09:00. Interpreted in the *snoozing analyst's* timezone, so a desk spread across zones each gets their own start of day rather than the server's.

```php
function snoozeWakeHour(PDO $conn): int
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 98.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 102 | `snoozeWakeHour` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md) - in [resolveSnoozeUntil()](/code/functions/resolveSnoozeUntil.md)
- [tickets/index.php](/code/files/tickets/index.php.md)

