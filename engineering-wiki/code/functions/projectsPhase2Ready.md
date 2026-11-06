# projectsPhase2Ready()

Has Database Verification created the phase 2 tables? The portfolio query names them.

```php
function projectsPhase2Ready(PDO $conn): bool
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 195.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 199 | `projectsPhase2Ready` | read [project_tolerances](/code/tables/project_tolerances.md) |  |
| 199 | `projectsPhase2Ready` | read [project_raid](/code/tables/project_raid.md) |  |

## Called from

- [includes/people.php](/code/files/includes/people.php.md) - in [peopleProjects()](/code/functions/peopleProjects.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectExceptionColumns()](/code/functions/projectExceptionColumns.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`

