# domainHttpGet()

One outbound GET. Never throws.

```php
function domainHttpGet(string $url, string $accept = 'application/json', int $timeout = 12): array
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$url` | `string` |  | the full http(s) URL to fetch |
| `$accept` | `string` | `'application/json'` | the Accept header value |
| `$timeout` | `int` | `12` | whole-request timeout in seconds (connecting is capped at 6) |

**Returns:** array{0:int,1:string,2:string} [http code (0 = no answer), body, error]

## Calls

[sslApplyCurl()](/code/functions/sslApplyCurl.md)

## Called from

- [includes/domains/dns.php](/code/files/includes/domains/dns.php.md) - in [domainDnsDoh()](/code/functions/domainDnsDoh.md)
- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainRdapBootstrap()](/code/functions/domainRdapBootstrap.md), [domainRdapLookup()](/code/functions/domainRdapLookup.md)
- [includes/domains/watch.php](/code/files/includes/domains/watch.php.md) - in [domainCtScan()](/code/functions/domainCtScan.md)

