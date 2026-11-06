# projectLinkSearch()

Records of one kind that could be linked: matching `$q`, in the project's company where that applies, visible to the analyst, not already linked. At most 20.

```php
function projectLinkSearch(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    string $kind,
    string $q
): array
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 333.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the link kind to search, e.g. 'ticket' |
| `$q` | `string` |  | the search text as typed; for changes and problems its digits also match the id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 346 | `projectLinkSearch` | read [assets](/code/tables/assets.md) |  |
| 350 | `projectLinkSearch` | read [changes](/code/tables/changes.md) |  |
| 354 | `projectLinkSearch` | read [tickets](/code/tables/tickets.md) |  |
| 358 | `projectLinkSearch` | read [cmdb_objects](/code/tables/cmdb_objects.md) |  |
| 362 | `projectLinkSearch` | read [problems](/code/tables/problems.md) |  |
| 366 | `projectLinkSearch` | read [contracts](/code/tables/contracts.md) |  |
| 372 | `projectLinkSearch` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [projectLinkDescribe()](/code/functions/projectLinkDescribe.md), [projectLinkKind()](/code/functions/projectLinkKind.md), [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectLinkProjectTenant()](/code/functions/projectLinkProjectTenant.md), [projectLinksReady()](/code/functions/projectLinksReady.md), [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)

## Called from

- [api/projects/links.php](/code/files/api/projects/links.php.md)

