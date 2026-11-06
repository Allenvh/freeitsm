# domainDateOnly()

Any date string → Y-m-d, or null.

```php
function domainDateOnly(string $raw): ?string
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 335.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$raw` | `string` |  | a date as a registry wrote it - ISO, yyyy.mm.dd, dd.mm.yyyy or anything `strtotime()` reads |

**Returns:** `?string`

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainRdapLookup()](/code/functions/domainRdapLookup.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

