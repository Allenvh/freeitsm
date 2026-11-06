# projectLinkAdd()

Links a record (asset, ticket, contract...) to a project and records it in the project's history.

Both ends must be visible to the actor and the actor must be able to change the
project. Returns false when the link already existed.

```php
function projectLinkAdd(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    string $kind,
    int $targetId
): bool
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 279.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the link kind, e.g. 'asset' |
| `$targetId` | `int` |  | the id of the record to link |

**Returns:** `bool`

## Calls

[projectLinkDescribe()](/code/functions/projectLinkDescribe.md), [projectLinkKind()](/code/functions/projectLinkKind.md), [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectLinkProjectTenant()](/code/functions/projectLinkProjectTenant.md), [projectLinksReady()](/code/functions/projectLinksReady.md), [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)

## Called from

- [api/projects/links.php](/code/files/api/projects/links.php.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::linkQuietly`

