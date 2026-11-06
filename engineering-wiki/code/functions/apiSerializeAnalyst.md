# apiSerializeAnalyst()

Shapes an analyst row for the API - id, username, name, email, active and admin flags, sign-in provider and dates. Never the password hash or MFA secret.

```php
function apiSerializeAnalyst(array $a): array
```

Defined in [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `array` |  | the analysts row as AnalystsService::load returns it |

**Returns:** `array`

## Calls

[apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/analysts.php](/code/files/api/v1/resources/analysts.php.md) - in [apiAnalystsCreate()](/code/functions/apiAnalystsCreate.md), [apiAnalystsGet()](/code/functions/apiAnalystsGet.md), [apiAnalystsUpdate()](/code/functions/apiAnalystsUpdate.md)

