# domainTlsNameMatches()

Does `$host` appear in the certificate's names (with one-label wildcards)?

```php
function domainTlsNameMatches(string $host, array $sans): bool
```

Defined in [includes/domains/tls.php](/code/files/includes/domains/tls.php.md), line 95.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$host` | `string` |  | the host name that was asked for |
| `$sans` | `array` |  | the certificate's DNS names, lower case; "*.example.com" matches one label |

**Returns:** `bool`

## Called from

- [includes/domains/tls.php](/code/files/includes/domains/tls.php.md) - in [domainTlsCertificate()](/code/functions/domainTlsCertificate.md)

