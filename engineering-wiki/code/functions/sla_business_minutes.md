# sla_business_minutes()

Pure function: total business minutes in the interval [start, end] given the calendar's timezone, weekly working hours, and holiday list.

```php
function sla_business_minutes(
    DateTimeImmutable $start,
    DateTimeImmutable $end,
    array $calendar
): int
```

Defined in [includes/sla.php](/code/files/includes/sla.php.md), line 286.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$start` | `DateTimeImmutable` |  | UTC |
| `$end` | `DateTimeImmutable` |  | UTC |
| `$calendar` | `array` |  | { timezone, hours: [{weekday, start_time, end_time}], holidays: [{holiday_date}] } |

**Returns:** `int`

## Called from

- [includes/sla.php](/code/files/includes/sla.php.md) - in [sla_elapsed_business_minutes()](/code/functions/sla_elapsed_business_minutes.md)

