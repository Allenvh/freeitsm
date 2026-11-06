# apiRateLimit()

Rate limiting — fixed one-minute windows in api_key_rate_limits, same mechanism as the proven api_rate_limits implementation. Default limit comes from system_settings.api_rate_limit_per_minute (fallback 60); a key can carry its own override. Fails open if the rate-limit table errors.

```php
function apiRateLimit(PDO $conn, int $keyId, ?int $override): void
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 123.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$keyId` | `int` |  | the api_keys id the request came in on |
| `$override` | `?int` |  | the key's own rate_limit_per_minute; null or 0 uses the system setting |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 129 | `apiRateLimit` | read [system_settings](/code/tables/system_settings.md) |  |
| 142 | `apiRateLimit` | **insert** [api_key_rate_limits](/code/tables/api_key_rate_limits.md) (`api_key_id`, `request_count`, `window_start`) |  |
| 148 | `apiRateLimit` | read [api_key_rate_limits](/code/tables/api_key_rate_limits.md) |  |
| 152 | `apiRateLimit` | **delete** [api_key_rate_limits](/code/tables/api_key_rate_limits.md) |  |

## Calls

[apiError()](/code/functions/apiError.md)

## Called from

- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiAuthenticate()](/code/functions/apiAuthenticate.md)

