# projectAlertsScan()

Compare each live project with what was seen last, and fire what changed. `$projectId` = one project (after a change to it); null = all of them.

```php
function projectAlertsScan(PDO $conn, ?int $projectId = null): array
```

Defined in [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), line 150.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` | `null` | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{health:int, breaches:int, stages_due:int, milestones:int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 154 | `projectAlertsScan` | read [projects](/code/tables/projects.md) |  |
| 168 | `projectAlertsScan` | **update** [projects](/code/tables/projects.md) (`alert_health`, `alert_exceptions`) |  |
| 203 | `projectAlertsScan` | read [projects](/code/tables/projects.md) |  |

## Calls

[projectAlertRows()](/code/functions/projectAlertRows.md), [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md), [projectAlertsMilestones()](/code/functions/projectAlertsMilestones.md), [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md), [projectAlertsStagesDue()](/code/functions/projectAlertsStagesDue.md), [projectAlertsStalled()](/code/functions/projectAlertsStalled.md), [projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md), [projectFlowSnapshot()](/code/functions/projectFlowSnapshot.md), [tasksContractorReminders()](/code/functions/tasksContractorReminders.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsRun()](/code/functions/projectAlertsRun.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::afterChange`

