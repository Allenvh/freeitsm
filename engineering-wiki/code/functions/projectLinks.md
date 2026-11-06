# projectLinks()

Everything linked to one project, per kind, for this analyst: a kind they cannot use is left out entirely; within a kind, rows they cannot see are dropped.

```php
function projectLinks(PDO $conn, ActorContext $ctx, int $projectId): array
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 253.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[projectLinkDescribe()](/code/functions/projectLinkDescribe.md), [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectLinkKinds()](/code/functions/projectLinkKinds.md), [projectLinkProjectTenant()](/code/functions/projectLinkProjectTenant.md), [projectLinksReady()](/code/functions/projectLinksReady.md), [projectLinkTargetOk()](/code/functions/projectLinkTargetOk.md)

## Called from

- [api/projects/links.php](/code/files/api/projects/links.php.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectLinks()](/code/functions/apiProjectLinks.md)

