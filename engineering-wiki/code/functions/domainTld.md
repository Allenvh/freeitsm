# domainTld()

The last label: "co.uk" is not a TLD, "uk" is.

```php
function domainTld(string $name): string
```

Defined in [includes/domains/names.php](/code/files/includes/domains/names.php.md), line 89.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | a domain or host name |

**Returns:** `string`

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainLookup()](/code/functions/domainLookup.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md), [domainLookupPace()](/code/functions/domainLookupPace.md), [domainTransferLockNotApplicable()](/code/functions/domainTransferLockNotApplicable.md)

