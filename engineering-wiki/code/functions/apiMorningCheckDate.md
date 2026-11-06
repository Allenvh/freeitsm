# apiMorningCheckDate()

Strict YYYY-MM-DD — 422 on garbage (the UI silently substitutes today).

```php
function apiMorningCheckDate(string $value, string $field): string
```

Defined in [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md), line 128.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` | `string` |  | the caller-supplied date (untrusted), expected as YYYY-MM-DD |
| `$field` | `string` |  | the field or query parameter name, quoted in the 422 message |

**Returns:** `string`

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/resources/morning_checks.php](/code/files/api/v1/resources/morning_checks.php.md) - in [apiMorningChecksBoard()](/code/functions/apiMorningChecksBoard.md), [apiMorningCheckResultsList()](/code/functions/apiMorningCheckResultsList.md)

