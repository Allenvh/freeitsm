# domainParseSslHosts()

The extra certificate hosts, cleaned: one per entry, only ones that belong to the domain, de-duplicated. The apex and www are always checked and are not listed here.

```php
function domainParseSslHosts(?string $raw, string $domain): array
```

Defined in [includes/domains/names.php](/code/files/includes/domains/names.php.md), line 117.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `?string` |  | the extra hosts as typed - separated by spaces, commas or semicolons; null for none |
| `$domain` | `string` |  | the stored domain name the hosts must belong to |

**Returns:** `array`

## Calls

[domainHostBelongsTo()](/code/functions/domainHostBelongsTo.md), [domainNormaliseHost()](/code/functions/domainNormaliseHost.md)

## Called from

- [includes/domains/checks.php](/code/files/includes/domains/checks.php.md) - in [domainRunChecks()](/code/functions/domainRunChecks.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::validateField`

