# domainNormaliseStatuses()

"client transfer prohibited" (RDAP) and "clientTransferProhibited https://icann.org/epp#…" (WHOIS) → "client transfer prohibited".

```php
function domainNormaliseStatuses(array $raw): array
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 246.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `array` |  | status strings as the registry sent them, RDAP words or WHOIS EPP codes with URLs |

**Returns:** `array`

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainRdapLookup()](/code/functions/domainRdapLookup.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

