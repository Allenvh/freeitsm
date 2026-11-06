# apiAuthenticate()

Authenticate the request and return the key row (with decoded permissions and company scope). Emits 401/403/429 and stops on failure.

```php
function apiAuthenticate(PDO $conn): array
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 20.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 | `apiAuthenticate` | read [api_keys](/code/tables/api_keys.md), read [analysts](/code/tables/analysts.md) |  |
| 56 | `apiAuthenticate` | **update** [api_keys](/code/tables/api_keys.md) (`last_used_at`, `last_used_ip`) |  |

## Calls

[apiEffectiveCompanyScope()](/code/functions/apiEffectiveCompanyScope.md), [apiError()](/code/functions/apiError.md), [apiExtractKey()](/code/functions/apiExtractKey.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiRateLimit()](/code/functions/apiRateLimit.md), [apiV1NormalisePermissions()](/code/functions/apiV1NormalisePermissions.md)

## Called from

- [api/mcp/index.php](/code/files/api/mcp/index.php.md)
- [api/v1/index.php](/code/files/api/v1/index.php.md)

