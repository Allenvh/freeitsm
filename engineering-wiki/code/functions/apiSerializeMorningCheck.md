# apiSerializeMorningCheck()

Shapes a morning check row for the API - name, description, active flag, order and dates.

```php
function apiSerializeMorningCheck(array $r): array
```

Defined in [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), line 42.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a morningChecks_Checks row (PascalCase columns) |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md) - in [apiMorningChecksGet()](/code/functions/apiMorningChecksGet.md), [apiMorningChecksCreate()](/code/functions/apiMorningChecksCreate.md), [apiMorningChecksUpdate()](/code/functions/apiMorningChecksUpdate.md)

