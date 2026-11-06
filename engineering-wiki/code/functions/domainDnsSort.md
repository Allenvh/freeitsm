# domainDnsSort()

Puts records in a stable order so answers compare equal - MX by priority then host, CAA as given, everything else de-duplicated and sorted.

```php
function domainDnsSort(string $type, array $recs): array
```

Defined in [includes/domains/dns.php](/code/files/includes/domains/dns.php.md), line 150.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$type` | `string` |  | record type in upper case - decides how the records are ordered |
| `$recs` | `array` |  | the normalised records, in the shape `domainDns()` describes for that type |

**Returns:** `array`

## Called from

- [includes/domains/dns.php](/code/files/includes/domains/dns.php.md) - in [domainDnsSystem()](/code/functions/domainDnsSystem.md), [domainDnsDoh()](/code/functions/domainDnsDoh.md)

