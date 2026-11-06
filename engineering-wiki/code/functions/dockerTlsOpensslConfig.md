# dockerTlsOpensslConfig()

The OpenSSL config both certificates are made from.

PHP's openssl functions can only add extensions (subjectAltName, name
constraints and so on) through a config file, so one is written to a temp
file for the duration. Passing it explicitly also avoids depending on the
system openssl.cnf, which on some PHP builds is missing altogether.

```php
function dockerTlsOpensslConfig(string $host, ?string $ip): string
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 272.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$host` | `string` |  | the host name for the subjectAltName and the authority's name constraint |
| `$ip` | `?string` |  | an IP address to add to both as well, or null for a name only |

**Returns:** `string`

## Calls

[dockerTlsSingleAddressMask()](/code/functions/dockerTlsSingleAddressMask.md)

## Called from

- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsGenerate()](/code/functions/dockerTlsGenerate.md)

