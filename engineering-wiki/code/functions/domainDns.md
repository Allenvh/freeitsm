# domainDns()

Asks one DNS question through the chosen resolver and returns the normalised answer, cached for the request.

'auto' means DNS-over-HTTPS (Google) on Windows and the system resolver
elsewhere; DS, DNSKEY and an unsupported CAA always go over DoH, and a failed
system lookup is retried over DoH.

```php
function domainDns(string $name, string $type, string $resolver = 'system'): array
```

Defined in [includes/domains/dns.php](/code/files/includes/domains/dns.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the DNS name to ask about, e.g. 'example.com' or '_dmarc.example.com' |
| `$type` | `string` |  | record type - A, AAAA, NS, CNAME, MX, TXT, CAA, DS or DNSKEY (any case) |
| `$resolver` | `string` | `'system'` | auto \| system \| google \| cloudflare (the domain_dns_resolver setting); '' means auto |

**Returns:** array{ok:bool, nxdomain:bool, records:array, error:?string} records: A/AAAA → list of IPs; NS/CNAME → host names; MX → [[pri, host]]; TXT → strings; CAA → [[flags, tag, value]]; DS → [string] (presence is what matters)

## Calls

[domainDnsDoh()](/code/functions/domainDnsDoh.md), [domainDnsSystem()](/code/functions/domainDnsSystem.md)

## Called from

- [includes/domains/checks.php](/code/files/includes/domains/checks.php.md) - in [domainRunChecks()](/code/functions/domainRunChecks.md)
- [includes/domains/dns.php](/code/files/includes/domains/dns.php.md) - in [domainTxtStartingWith()](/code/functions/domainTxtStartingWith.md)
- [includes/domains/watch.php](/code/files/includes/domains/watch.php.md) - in [domainLookalikeScan()](/code/functions/domainLookalikeScan.md)

