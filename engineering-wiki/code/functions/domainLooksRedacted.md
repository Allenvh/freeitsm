# domainLooksRedacted()

True when a registrant value is a privacy placeholder ("REDACTED", "Domains By Proxy", GDPR wording) rather than a real name.

```php
function domainLooksRedacted(string $v): bool
```

Defined in [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md), line 325.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$v` | `string` |  | the registrant name or organisation the registry gave |

**Returns:** `bool`

## Called from

- [includes/domains/lookup.php](/code/files/includes/domains/lookup.php.md) - in [domainRdapLookup()](/code/functions/domainRdapLookup.md), [domainWhoisLookup()](/code/functions/domainWhoisLookup.md)

