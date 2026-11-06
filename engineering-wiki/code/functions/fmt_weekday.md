# fmt_weekday()

'Tuesday' / 'Tue'

```php
function fmt_weekday(?string $utc, bool $short = false): string
```

Defined in [includes/timezone.php](/code/files/includes/timezone.php.md), line 583.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$utc` | `?string` |  | a UTC datetime as stored; null or '' gives '', an unparseable value comes back as it is |
| `$short` | `bool` | `false` | true for the abbreviated name ('Tue') |

**Returns:** `string`

## Calls

[_fmt_dt()](/code/functions/_fmt_dt.md)

*Nothing calls this function by name.*

