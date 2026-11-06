# intuneGraphGet()

GET a Graph URL, retrying on 429/5xx with Retry-After honoured.

```php
function intuneGraphGet(string $url, array $settings, int $maxRetries = 5): array
```

Defined in [includes/intune.php](/code/files/includes/intune.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full Graph URL, including any query string or an `@odata`.nextLink |
| `$settings` | `array` |  | the connection settings from `intuneGetSettings()`, used to get the token |
| `$maxRetries` | `int` | `5` | how many attempts in all before giving up on a 429, 5xx or network failure |

**Returns:** `array`

## Calls

[intuneGetToken()](/code/functions/intuneGetToken.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/intune.php](/code/files/includes/intune.php.md) - in [intuneRunSync()](/code/functions/intuneRunSync.md), [intuneFetchDeviceApps()](/code/functions/intuneFetchDeviceApps.md)

