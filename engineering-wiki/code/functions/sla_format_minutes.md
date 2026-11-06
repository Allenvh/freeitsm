# sla_format_minutes()

Format a minute count as a short human-friendly string: "1h 30m", "45m", "2h", "-15m" (negative).

```php
function sla_format_minutes(int $minutes): string
```

Defined in [includes/sla.php](/code/files/includes/sla.php.md), line 388.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$minutes` | `int` |  | a number of minutes; negative gives a leading minus |

**Returns:** `string`

## Called from

- [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md) - in [sla_build_breach_email_body()](/code/functions/sla_build_breach_email_body.md)

