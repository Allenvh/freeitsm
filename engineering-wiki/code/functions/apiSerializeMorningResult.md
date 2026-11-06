# apiSerializeMorningResult()

Shapes a morning check result for the API. A result whose status has since been deleted is marked is_orphan, with the label it was saved under.

```php
function apiSerializeMorningResult(array $r): array
```

Defined in [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the morningChecks_Results row as `apiMorningResultSelect()` loads it, with CheckName and status label/colour |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md) - in [apiMorningCheckResultsGet()](/code/functions/apiMorningCheckResultsGet.md), [apiMorningCheckResultsRecord()](/code/functions/apiMorningCheckResultsRecord.md)

