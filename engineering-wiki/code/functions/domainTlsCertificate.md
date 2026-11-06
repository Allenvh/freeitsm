# domainTlsCertificate()

Reads the certificate a host presents - subject, issuer, dates, days left and names - then says whether it matches the host name and whether the chain verifies against the trusted CA bundle.

```php
function domainTlsCertificate(string $host, int $port = 443, int $timeout = 8): array
```

Defined in [includes/domains/tls.php](/code/files/includes/domains/tls.php.md), line 33.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$host` | `string` |  | the host name to connect to and to send as SNI |
| `$port` | `int` | `443` | the TCP port to try |
| `$timeout` | `int` | `8` | connect timeout in seconds, for each of the two handshakes |

**Returns:** array{ok:bool, host:string, error:?string, subject:?string, issuer:?string, valid_from:?string, valid_to:?string, days_left:?int, sans:array, hostname_match:?bool, chain_valid:?bool}

## Calls

[domainTlsNameMatches()](/code/functions/domainTlsNameMatches.md), [sslResolveCaBundle()](/code/functions/sslResolveCaBundle.md)

## Called from

- [includes/domains/checks.php](/code/files/includes/domains/checks.php.md) - in [domainRunChecks()](/code/functions/domainRunChecks.md)

