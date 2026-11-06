# apiLoadMorningResult()

Loads one morning check result row, or answers 404 and stops.

```php
function apiLoadMorningResult(PDO $conn, int $resultId): array
```

Defined in [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), line 112.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$resultId` | `int` |  | the morningChecks_Results ResultID |

**Returns:** `array`

## Calls

[apiError()](/code/functions/apiError.md), [apiMorningResultSelect()](/code/functions/apiMorningResultSelect.md)

## Called from

- [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md) - in [apiMorningCheckResultsGet()](/code/functions/apiMorningCheckResultsGet.md), [apiMorningCheckResultsRecord()](/code/functions/apiMorningCheckResultsRecord.md)

