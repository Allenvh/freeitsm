# projectDispatch()

Dispatch, never throwing: an event must not break the change that caused it.

```php
function projectDispatch(string $event, array $payload): void
```

Defined in [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), line 93.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$event` | `string` |  | the workflow event name, e.g. 'project.health_changed' |
| `$payload` | `array` |  | the event's data, as WorkflowEngine::dispatch() takes it |

**Returns:** `void`

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md), [projectAlertsStagesDue()](/code/functions/projectAlertsStagesDue.md), [projectAlertsMilestones()](/code/functions/projectAlertsMilestones.md)
- [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) - in [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md), [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)
- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::runSchedules`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::deleteProject`, `ProjectsService::stageClosed`, `ProjectsService::raidEscalated`, `ProjectsService::signoffEvent`, `ProjectsService::proposalEvent`, `ProjectsService::changeEvent`, `ProjectsService::milestoneReached`, `ProjectsService::dispatch`

