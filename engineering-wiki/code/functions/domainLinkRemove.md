# domainLinkRemove()

Removes a domain link, under the same access rules as adding one; returns false when there was nothing to remove.

```php
function domainLinkRemove(
    PDO $conn,
    int $analystId,
    int $domainId,
    string $kind,
    int $targetId
): bool
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 239.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain to unlink from |
| `$kind` | `string` |  | the link kind - cmdb \| service \| ticket \| article (untrusted; unknown throws) |
| `$targetId` | `int` |  | the id of the linked record |

**Returns:** `bool`

## Calls

[domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md), [domainLinkKind()](/code/functions/domainLinkKind.md), [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md), [domainLinksReady()](/code/functions/domainLinksReady.md), [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

