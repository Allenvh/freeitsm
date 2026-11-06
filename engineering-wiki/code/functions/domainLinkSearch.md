# domainLinkSearch()

Records of one kind that could be linked to a domain: matching `$q`, in the domain's company, visible to the analyst, not already linked. At most 20.

```php
function domainLinkSearch(PDO $conn, int $analystId, int $domainId, string $kind, string $q): array
```

Defined in [includes/domains/links.php](/code/files/includes/domains/links.php.md), line 262.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$domainId` | `int` |  | the domain the picker is for |
| `$kind` | `string` |  | which kind of record to search - cmdb \| service \| ticket \| article |
| `$q` | `string` |  | what the user typed; matched anywhere in the name, title, subject or ticket number |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 274 | `domainLinkSearch` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 279 | `domainLinkSearch` | read [tickets](/code/tables/tickets.md) |  |
| 284 | `domainLinkSearch` | read [status_services](/code/tables/status_services.md) |  |
| 290 | `domainLinkSearch` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[domainLinkDescribe()](/code/functions/domainLinkDescribe.md), [domainLinkDomainTenant()](/code/functions/domainLinkDomainTenant.md), [domainLinkKind()](/code/functions/domainLinkKind.md), [domainLinkKindAllowed()](/code/functions/domainLinkKindAllowed.md), [domainLinksReady()](/code/functions/domainLinksReady.md), [domainLinkTargetOk()](/code/functions/domainLinkTargetOk.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

## Called from

- [api/domains/links.php](/code/files/api/domains/links.php.md)

