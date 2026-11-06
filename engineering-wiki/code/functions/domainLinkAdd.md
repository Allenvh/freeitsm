# domainLinkAdd()

Links a record of one kind to a domain, when the analyst can reach both modules and both records in the domain's company; returns false when the link already existed.

```php
function domainLinkAdd(PDO $conn, int $analystId, int $domainId, string $kind, int $targetId): bool
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 219.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain to link from |
| `$kind` | `string` |  | the link kind - cmdb \| service \| ticket \| article (untrusted; unknown throws) |
| `$targetId` | `int` |  | the id of the record to link to |

**Returns:** `bool`

## Calls

[domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md), [domainLinkKind()](/code/functions/domainLinkKind.md), [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md), [domainLinksReady()](/code/functions/domainLinksReady.md), [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

