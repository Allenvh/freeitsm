# dockerTlsFingerprintNormalise()

Lower-case hex with no separators, so PHP's and the openssl CLI's agree.

```php
function dockerTlsFingerprintNormalise(string $fp): string
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 134.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$fp` | `string` |  | a fingerprint in any format, e.g. "SHA256 Fingerprint=AB:CD:..." from openssl or bare hex from PHP |

**Returns:** `string`

## Called from

- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsCertInfo()](/code/functions/dockerTlsCertInfo.md), [dockerTlsState()](/code/functions/dockerTlsState.md)

