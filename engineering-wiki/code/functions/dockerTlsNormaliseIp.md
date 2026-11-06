# dockerTlsNormaliseIp()

A valid IPv4 or IPv6 address, or null. Empty input is allowed and gives null.

```php
function dockerTlsNormaliseIp(string $input): ?string
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 87.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$input` | `string` |  | what the administrator typed (untrusted); surrounding spaces and [ ] are stripped |

**Returns:** `?string`

## Called from

- [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md)

