# rpParseDate()

Parses a YYYY-MM-DD string as midnight in the given timezone; null when it is not a valid date.

```php
function rpParseDate($s, DateTimeZone $zone): ?DateTimeImmutable
```

Defined in [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md), line 92.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$s` |  |  | the date to parse, 'Y-m-d'; anything else gives null |
| `$zone` | `DateTimeZone` |  | the timezone the midnight is in |

**Returns:** `?DateTimeImmutable`

## Called from

- [includes/report_packs/criteria.php](/code/files/includes/report_packs/criteria.php.md) - in [rpResolveRange()](/code/functions/rpResolveRange.md)

