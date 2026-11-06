# _fmt_dt()

Internal: UTC string -> DateTime in the display zone, or null if unparseable.

```php
function _fmt_dt(?string $utc): ?DateTime
```

Defined in [includes/timezone.php](/code/files/includes/timezone.php.md), line 438.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$utc` | `?string` |  | a UTC datetime as stored in the database; null or '' gives null |

**Returns:** `?DateTime`

## Called from

- [includes/timezone.php](/code/files/includes/timezone.php.md) - in [fmt_date()](/code/functions/fmt_date.md), [fmt_time()](/code/functions/fmt_time.md), [fmt_datetime()](/code/functions/fmt_datetime.md), [fmt_day_month()](/code/functions/fmt_day_month.md), [fmt_month_year()](/code/functions/fmt_month_year.md), [fmt_weekday()](/code/functions/fmt_weekday.md)

