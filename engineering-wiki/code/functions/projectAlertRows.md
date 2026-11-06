# projectAlertRows()

Live projects decorated with their health and exceptions, plus what the scan saw last.

```php
function projectAlertRows(PDO $conn, ?int $projectId = null): array
```

Defined in [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), line 122.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` | `null` | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 131 | `projectAlertRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectPriorityColumn()](/code/functions/projectPriorityColumn.md), [projectTaskStats()](/code/functions/projectTaskStats.md)

## Called from

- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::eventFor`

