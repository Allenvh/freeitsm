# sla_load_settings()

Load the seven SLA system_settings rows into a plain array.

```php
function sla_load_settings(PDO $conn): array
```

Defined in [includes/sla.php](/code/files/includes/sla.php.md), line 340.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 351 | `sla_load_settings` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/sla.php](/code/files/includes/sla.php.md) - in [sla_get_state()](/code/functions/sla_get_state.md)
- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_run_breach_check()](/code/functions/sla_run_breach_check.md)

