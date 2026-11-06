# domainDeriveFlags()

Locks and states the statuses imply.

⚠️ REGISTRY LOCK IS INFERRED. It is the three server*Prohibited statuses
together — which is how registries apply it, but a registry can set them for
other reasons too (a legal hold). The screen says "likely registry-locked".

```php
function domainDeriveFlags(array &$r, string $name = ''): void
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 270.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | the lookup result being built (domainBlankResult() shape) - its lock and state flags are set in place |
| `$name` | `string` | `''` | the domain name, used only to spot TLDs where a transfer lock does not apply ('' for none) |

**Returns:** `void`

## Calls

[domainTransferLockNotApplicable()](/code/functions/domainTransferLockNotApplicable.md)

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainRdapLookup()](/code/functions/domainRdapLookup.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

