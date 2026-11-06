# slcEnsureRenewalCategory()

Find or create the "Renewals" calendar category; returns its id (or null).

```php
function slcEnsureRenewalCategory(PDO $conn): ?int
```

Defined in [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md), line 134.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 136 | `slcEnsureRenewalCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 143 | `slcEnsureRenewalCategory` | **insert** [calendar_categories](/code/tables/calendar_categories.md) (`name`, `color`, `is_active`) |  |

## Called from

- [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md) - in [syncSoftwareLicenceCalendar()](/code/functions/syncSoftwareLicenceCalendar.md)

