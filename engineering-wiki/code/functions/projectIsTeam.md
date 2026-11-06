# projectIsTeam()

Is this analyst on the project's team: its manager, its creator or a member? (Members arrive with project_members; until that table exists the team is the manager and the creator.)

```php
function projectIsTeam(PDO $conn, int $analystId, array $project): bool
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 446.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - id, owner_analyst_id and created_by_id are read |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 451 | `projectIsTeam` | read [project_members](/code/tables/project_members.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |

## Called from

- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectCanChange()](/code/functions/projectCanChange.md)
- [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md) - in [projectVisibleTo()](/code/functions/projectVisibleTo.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::updateProject`

