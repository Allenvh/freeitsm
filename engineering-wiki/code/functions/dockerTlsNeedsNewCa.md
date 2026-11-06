# dockerTlsNeedsNewCa()

Would making a certificate for this name and IP need a NEW authority?

True when there is none yet, when it was made for something else (it is
name-constrained, so it cannot sign anything else), or when it would expire
before the new server certificate does.

```php
function dockerTlsNeedsNewCa(string $host, ?string $ip, string $dir = DOCKER_TLS_DIR): bool
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 235.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$host` | `string` |  | the host name the certificate is for, already through `dockerTlsNormaliseHost()` |
| `$ip` | `?string` |  | the IP address it also covers, or null for a name only |
| `$dir` | `string` | `DOCKER_TLS_DIR` | the certificate folder, normally DOCKER_TLS_DIR |

**Returns:** `bool`

## Calls

[dockerTlsCertInfo()](/code/functions/dockerTlsCertInfo.md)

## Called from

- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsGenerate()](/code/functions/dockerTlsGenerate.md)

