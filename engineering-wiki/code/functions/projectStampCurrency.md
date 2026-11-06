# projectStampCurrency()

Stamp the install currency on a project that has none, so a later change of default cannot relabel it.

```php
function projectStampCurrency(PDO $conn, int $projectId): void
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 87 | `projectStampCurrency` | **update** [projects](/code/tables/projects.md) (`currency`) |  |

## Calls

[projectInstallCurrency()](/code/functions/projectInstallCurrency.md)

## Called from

- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::decideChangeRequest`, `ProjectToolsService::saveBudgetLine`, `ProjectToolsService::addProjectRate`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`

