# dockerTlsJson()

Sends an HTTP status and the given body as JSON, then stops.

```php
function dockerTlsJson(array $body, int $status = 200): void
```

Defined in [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md), line 23.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$body` | `array` |  | the response to send as JSON |
| `$status` | `int` | `200` | the HTTP status to send |

**Returns:** `void`

## Called from

- [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md)

