# domainLinks()

Everything linked to one domain, per kind, for this analyst: a kind they cannot use is left out entirely; within a kind, rows they cannot see are dropped.

```php
function domainLinks(PDO $conn, int $analystId, int $domainId): array
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 196.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain whose links to list |

**Returns:** `array`

## Calls

[domainLinkDescribe()](/code/functions/domainLinkDescribe.md), [domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md), [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md), [domainLinkKinds()](/code/functions/domainLinkKinds.md), [domainLinksReady()](/code/functions/domainLinksReady.md), [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

