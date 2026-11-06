# dockerTlsSingleAddressMask()

The netmask that pins a name constraint to exactly one address.

```php
function dockerTlsSingleAddressMask(string $ip): string
```

Defined in [includes/docker_tls.php](/code/files/includes/docker_tls.php.md), line 254.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$ip` | `string` |  | a valid IPv4 or IPv6 address - decides which kind of mask comes back |

**Returns:** `string`

## Called from

- [includes/docker_tls.php](/code/files/includes/docker_tls.php.md) - in [dockerTlsOpensslConfig()](/code/functions/dockerTlsOpensslConfig.md)

