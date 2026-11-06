# projectLinkRemove()

Removes a link between a project and a record and records it in the project's history.

Needs the same rights as adding it. Returns false when there was no such link.

```php
function projectLinkRemove(
    PDO $conn,
    ActorContext $ctx,
    int $projectId,
    string $kind,
    int $targetId
): bool
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 307.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` |  | the link kind, e.g. 'asset' |
| `$targetId` | `int` |  | the id of the linked record |

**Returns:** `bool`

## Calls

[projectLinkDescribe()](/code/functions/projectLinkDescribe.md), [projectLinkKind()](/code/functions/projectLinkKind.md), [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectLinkProjectTenant()](/code/functions/projectLinkProjectTenant.md), [projectLinksReady()](/code/functions/projectLinksReady.md), [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)

## Called from

- [api/projects/links.php](/code/files/api/projects/links.php.md)

