# domainBaseline()

Build the baseline from a domain row plus the check snapshot.

```php
function domainBaseline(array $d, array $snapshot): array
```

Defined in [includes/domains/monitor.php](/code/files/includes/domains/monitor.php.md), line 35.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$d` | `array` |  | the domain row (registrar_name, nameservers, transfer_lock, registry_lock, dnssec) |
| `$snapshot` | `array` |  | the 'snapshot' from `domainRunChecks()` - array{dns_ns:string[],mx:string[],spf:string,dmarc:string} |

**Returns:** `array`

## Called from

- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::runChecks`

