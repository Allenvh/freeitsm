# sla_load_calendar()

Load a calendar with its hours + holidays nested. Returns null if not found.

```php
function sla_load_calendar(PDO $conn, int $calendar_id): ?array
```

Defined in [includes/sla.php](/code/files/includes/sla.php.md), line 366.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$calendar_id` | `int` |  | the SLA calendar (sla_calendars.id); an inactive one gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 367 | `sla_load_calendar` | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 372 | `sla_load_calendar` | read [sla_calendar_hours](/code/tables/sla_calendar_hours.md) |  |
| 376 | `sla_load_calendar` | read [sla_calendar_holidays](/code/tables/sla_calendar_holidays.md) |  |

## Called from

- [includes/sla.php](/code/files/includes/sla.php.md) - in [sla_get_state()](/code/functions/sla_get_state.md)
- [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md) - in [webchatIsOpenNow()](/code/functions/webchatIsOpenNow.md)

