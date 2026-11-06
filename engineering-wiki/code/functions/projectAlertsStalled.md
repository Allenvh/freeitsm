# projectAlertsStalled()

The nudges. `$projectId` = one project; null = all of them.

```php
function projectAlertsStalled(PDO $conn, ?int $projectId = null): int
```

Defined in [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md), line 80.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `?int` | `null` | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 92 | `projectAlertsStalled` | read [projects](/code/tables/projects.md) |  |
| 104 | `projectAlertsStalled` | read [project_change_requests](/code/tables/project_change_requests.md), read [projects](/code/tables/projects.md) |  |
| 119 | `projectAlertsStalled` | read [project_gate_items](/code/tables/project_gate_items.md), read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 130 | `projectAlertsStalled` | read [project_reports](/code/tables/project_reports.md), read [projects](/code/tables/projects.md) |  |
| 139 | `projectAlertsStalled` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |

## Calls

[projectAlertsAsSystem()](/code/functions/projectAlertsAsSystem.md), [projectControlReady()](/code/functions/projectControlReady.md), [projectDispatch()](/code/functions/projectDispatch.md), [projectEventPayload()](/code/functions/projectEventPayload.md), [projectIntakeReady()](/code/functions/projectIntakeReady.md), [projectNudgeDeciders()](/code/functions/projectNudgeDeciders.md), [projectNudgeProjects()](/code/functions/projectNudgeProjects.md), [projectProposalApprovers()](/code/functions/projectProposalApprovers.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md)

