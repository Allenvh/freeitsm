# domainLinkTargetOk()

Is the OTHER end of a link one this analyst may see, and (for company records) in the domain's company? `$domainTenant` null = do not compare.

```php
function domainLinkTargetOk(
    PDO $conn,
    int $analystId,
    string $kind,
    int $targetId,
    ?int $domainTenant
): bool
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 110.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the link kind - cmdb \| service \| ticket \| article |
| `$targetId` | `int` |  | the id of the record on the other end (a CI, service, ticket or article) |
| `$domainTenant` | `?int` |  | the domain's company to match for a CI or ticket; null skips the company check |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 120 | `domainLinkTargetOk` | read [tickets](/code/tables/tickets.md) |  |
| 125 | `domainLinkTargetOk` | read [status_services](/code/tables/status_services.md) |  |

## Calls

[analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [domainLinkTenantOf()](/code/functions/domainLinkTenantOf.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md)

## Called from

- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinks()](/code/functions/domainLinks.md), [domainLinkAdd()](/code/functions/domainLinkAdd.md), [domainLinkRemove()](/code/functions/domainLinkRemove.md), [domainLinkSearch()](/code/functions/domainLinkSearch.md), [domainsLinkedTo()](/code/functions/domainsLinkedTo.md), [domainLinkPickDomains()](/code/functions/domainLinkPickDomains.md)

