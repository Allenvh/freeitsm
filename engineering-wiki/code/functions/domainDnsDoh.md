# domainDnsDoh()

Answers a DNS question over DNS-over-HTTPS JSON from Google or Cloudflare, normalised and sorted; NXDOMAIN comes back as ok with nxdomain true.

```php
function domainDnsDoh(string $name, string $type, string $provider): array
```

Defined in [includes/domains/dns.php](/code/files/includes/domains/dns.php.md), line 102.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | the DNS name to ask about |
| `$type` | `string` |  | record type in upper case |
| `$provider` | `string` |  | 'cloudflare' for Cloudflare; anything else asks Google |

**Returns:** `array`

## Calls

[domainDnsSort()](/code/functions/domainDnsSort.md), [domainHttpGet()](/code/functions/domainHttpGet.md)

## Called from

- [includes/domains/dns.php](/code/files/includes/domains/dns.php.md) - in [domainDns()](/code/functions/domainDns.md)

