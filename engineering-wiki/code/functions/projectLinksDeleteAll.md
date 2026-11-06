# projectLinksDeleteAll()

Remove every link a project holds - called when the project is deleted.

```php
function projectLinksDeleteAll(PDO $conn, int $projectId): void
```

Defined in [includes/projects/links.php](/code/files/includes/projects/links.php.md), line 478.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[projectLinkKinds()](/code/functions/projectLinkKinds.md)

## Called from

- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::deleteProject`

