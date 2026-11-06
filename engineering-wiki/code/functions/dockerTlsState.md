# dockerTlsState()

Everything the screen needs to know, in one place.

```php
function dockerTlsState(string $dir = DOCKER_TLS_DIR): array
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 166.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$dir` | `string` | `DOCKER_TLS_DIR` | the certificate folder, normally DOCKER_TLS_DIR (/var/www/tls) |

**Returns:** `array`

## Calls

[dockerTlsCertInfo()](/code/functions/dockerTlsCertInfo.md), [dockerTlsFingerprintNormalise()](/code/functions/dockerTlsFingerprintNormalise.md), [storagePersistenceStatus()](/code/functions/storagePersistenceStatus.md)

## Called from

- [system/docker/index.php](/code/files/system/docker/index.php.md)

