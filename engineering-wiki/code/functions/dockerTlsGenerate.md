# dockerTlsGenerate()

Make the certificates. Returns ['new_ca' => bool, 'server' => info, 'ca' => info].

Reuses the existing authority when it was made for the same name and IP, so
renewing the server certificate never means visiting the PCs again.

```php
function dockerTlsGenerate(string $host, ?string $ip, string $dir = DOCKER_TLS_DIR): array
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 335.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$host` | `string` |  | the host name, already through `dockerTlsNormaliseHost()` |
| `$ip` | `?string` |  | the IP address to include too, already through `dockerTlsNormaliseIp()`, or null for none |
| `$dir` | `string` | `DOCKER_TLS_DIR` | the certificate folder to write into, normally DOCKER_TLS_DIR |

**Returns:** `array`

## Calls

[dockerTlsCertInfo()](/code/functions/dockerTlsCertInfo.md), [dockerTlsNeedsNewCa()](/code/functions/dockerTlsNeedsNewCa.md), [dockerTlsOpensslConfig()](/code/functions/dockerTlsOpensslConfig.md), [dockerTlsOpensslError()](/code/functions/dockerTlsOpensslError.md), [dockerTlsWriteFile()](/code/functions/dockerTlsWriteFile.md)

## Called from

- [api/system/docker_tls.php](/code/files/api/system/docker_tls.php.md)

