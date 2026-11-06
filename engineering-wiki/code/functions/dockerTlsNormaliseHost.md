# dockerTlsNormaliseHost()

Tidy what someone typed into a bare host name, or null if it is not one.

Forgiving about what people paste — "https://FreeITSM.internal:8443/" becomes
"freeitsm.internal" — and strict about the result, because it goes into a
certificate and an OpenSSL config file.

```php
function dockerTlsNormaliseHost(string $input): ?string
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$input` | `string` |  | what the administrator typed (untrusted) - may include a scheme, port or path |

**Returns:** `?string`

## Called from

- [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md)

