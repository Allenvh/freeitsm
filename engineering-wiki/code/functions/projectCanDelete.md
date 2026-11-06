# projectCanDelete()

Deleting is never "anyone": the project manager, its creator, or Manage Projects.

```php
function projectCanDelete(PDO $conn, int $analystId, array $project): bool
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 478.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - owner_analyst_id and created_by_id are read |

**Returns:** `bool`

## Calls

[projectIsManager()](/code/functions/projectIsManager.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [api/projects/reports.php](/code/files/api/projects/reports.php.md)
- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::approve`, `ProjectReportsService::delete`, `ProjectReportsService::send`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::deleteProject`

