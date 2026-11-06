# vcdHttp()

One HTTP call. Returns [status code, body, access token from the response headers]. Throws only when the server cannot be reached at all; HTTP errors are returned.

```php
function vcdHttp(
    array $c,
    string $method,
    string $path,
    array $headers,
    ?string $bearer = null,
    ?string $basic = null
): array
```

Defined in [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md), line 63.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$c` | `array` |  | the connection row from `vcdLoadConnection()` (host and verify_ssl are used) |
| `$method` | `string` |  | the HTTP method, e.g. 'GET', 'POST', 'DELETE' |
| `$path` | `string` |  | the path after the host, starting with '/', e.g. '/cloudapi/1.0.0/sessions' |
| `$headers` | `array` |  | extra header lines, e.g. from `vcdAcceptJson()` |
| `$bearer` | `?string` | `null` | a session token from `vcdLogin()`; takes precedence over `$basic` |
| `$basic` | `?string` | `null` | base64 "user@org:password" for the login call only |

**Returns:** `array`

## Called from

- [includes/vcloud_sync.php](/code/files/includes/vcloud_sync.php.md) - in [vcdLogin()](/code/functions/vcdLogin.md), [vcdLogout()](/code/functions/vcdLogout.md), [vcdTryGet()](/code/functions/vcdTryGet.md)

