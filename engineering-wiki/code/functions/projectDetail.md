# projectDetail()

One project for its page: the row, its time boxes with their counts, its tasks and its history.

```php
function projectDetail(PDO $conn, array $row): array
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 373.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$row` | `array` |  | the projects row, already loaded and checked as visible |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 376 | `projectDetail` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |
| 384 | `projectDetail` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [project_stages](/code/tables/project_stages.md) |  |
| 392 | `projectDetail` | read [tasks](/code/tables/tasks.md) |  |
| 397 | `projectDetail` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md), read [task_priorities](/code/tables/task_priorities.md), read [analysts](/code/tables/analysts.md), read [teams](/code/tables/teams.md) |  |
| 414 | `projectDetail` | read [project_audit](/code/tables/project_audit.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectDecorate()](/code/functions/projectDecorate.md), [projectEstimatesReady()](/code/functions/projectEstimatesReady.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [tasksWithContractors()](/code/functions/tasksWithContractors.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)

