# projectAlertsOverdueDigest()

The overdue-task digests. `$projectId` = one project; null = every live one.

```php
function projectAlertsOverdueDigest(PDO $conn, ?int $projectId = null): int
```

Defined in [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` | `null` | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `projectAlertsOverdueDigest` | read [suppliers](/code/tables/suppliers.md) |  |
| 39 | `projectAlertsOverdueDigest` | read [tasks](/code/tables/tasks.md), read [projects](/code/tables/projects.md), read [task_statuses](/code/tables/task_statuses.md), read [analysts](/code/tables/analysts.md) |  |
| 54 | `projectAlertsOverdueDigest` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## Calls

[projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md), [projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md), [projectNudgeProjects()](/code/functions/projectNudgeProjects.md), [projectSetting()](/code/functions/projectSetting.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md)

