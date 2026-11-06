# projectVisibleTo()

May this analyst see this project row (needs id, owner_analyst_id, created_by_id, visibility)?

```php
function projectVisibleTo(PDO $conn, int $analystId, array $project): bool
```

Defined in [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), line 92.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - id, owner_analyst_id, created_by_id and visibility are read |

**Returns:** `bool`

## Calls

[projectIsTeam()](/code/functions/projectIsTeam.md), [projectSeesAllPrivate()](/code/functions/projectSeesAllPrivate.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiLoadProject()](/code/functions/apiLoadProject.md)
- [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md) - in [projectIdVisibleTo()](/code/functions/projectIdVisibleTo.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::loadForActor`

