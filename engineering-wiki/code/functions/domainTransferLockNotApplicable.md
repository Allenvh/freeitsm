# domainTransferLockNotApplicable()

Registries where a registrar "transfer lock" is not how transfers are controlled, so its absence is not a weakness. .uk: Nominet registrar tags.

```php
function domainTransferLockNotApplicable(string $name): bool
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 483.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$name` | `string` |  | a stored domain name; only its TLD is read |

**Returns:** `bool`

## Calls

[domainTld()](/code/functions/domainTld.md)

## Called from

- [includes/domains/checks.php](/code/files/includes/domains/checks.php.md) - in [domainRunChecks()](/code/functions/domainRunChecks.md)
- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainDeriveFlags()](/code/functions/domainDeriveFlags.md)

