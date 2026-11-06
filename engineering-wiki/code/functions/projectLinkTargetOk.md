# projectLinkTargetOk()

Is the OTHER end of a link one this analyst may see, and (for company records) in the project's company?

```php
function projectLinkTargetOk(
    PDO $conn,
    int $analystId,
    string $kind,
    int $targetId,
    ?int $projectTenant
): bool
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 129.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the link kind, e.g. 'ticket' |
| `$targetId` | `int` |  | the id of the record at the other end |
| `$projectTenant` | `?int` |  | the project's company; null skips the same-company check (the record's own page) |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 144 | `projectLinkTargetOk` | read [tickets](/code/tables/tickets.md) |  |

## Calls

[analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessChange()](/code/functions/analystCanAccessChange.md), [analystCanAccessCmdbObject()](/code/functions/analystCanAccessCmdbObject.md), [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [contractCanView()](/code/functions/contractCanView.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [projectLinkKinds()](/code/functions/projectLinkKinds.md), [projectLinkTenantOf()](/code/functions/projectLinkTenantOf.md)

## Called from

- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinks()](/code/functions/projectLinks.md), [projectLinkAdd()](/code/functions/projectLinkAdd.md), [projectLinkRemove()](/code/functions/projectLinkRemove.md), [projectLinkSearch()](/code/functions/projectLinkSearch.md), [projectsLinkedTo()](/code/functions/projectsLinkedTo.md), [projectsPickableFor()](/code/functions/projectsPickableFor.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveRaid`

