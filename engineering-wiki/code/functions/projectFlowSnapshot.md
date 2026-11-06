# projectFlowSnapshot()

Record today's counts by status for these projects (top-level tasks). Quiet: never breaks its caller.

```php
function projectFlowSnapshot(PDO $conn, array $projectIds): void
```

Defined in [includes/projects/flow.php](/code/files/includes/projects/flow.php.md), line 33.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectIds` | `array` |  | the projects to record today's counts for |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 | `projectFlowSnapshot` | read [tasks](/code/tables/tasks.md) |  |
| 43 | `projectFlowSnapshot` | **delete** [project_task_flow](/code/tables/project_task_flow.md) |  |
| 44 | `projectFlowSnapshot` | **insert** [project_task_flow](/code/tables/project_task_flow.md) (`project_id`, `day`, `status_id`, `task_count`) |  |

## Calls

[projectFlowReady()](/code/functions/projectFlowReady.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md)

