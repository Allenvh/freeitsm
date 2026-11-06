# domainBlankResult()

The result shape every source fills in.

```php
function domainBlankResult(string $source): array
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 228.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$source` | `string` |  | where the answer came from - 'rdap' or 'whois' |

**Returns:** `array`

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainRdapLookup()](/code/functions/domainRdapLookup.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

