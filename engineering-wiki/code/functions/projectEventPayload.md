# projectEventPayload()

The project as every project event carries it.

```php
function projectEventPayload(array $p): array
```

Defined in [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md), line 70.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$p` | `array` |  | a projects row as `projectAlertRows()` gives it (shown_health and owner_name included when present) |

**Returns:** `array`

## Calls

[projectCode()](/code/functions/projectCode.md)

## Called from

- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsScan()](/code/functions/projectAlertsScan.md), [projectAlertsStagesDue()](/code/functions/projectAlertsStagesDue.md), [projectAlertsMilestones()](/code/functions/projectAlertsMilestones.md)
- [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) - in [projectAlertsBenefits()](/code/functions/projectAlertsBenefits.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsOverdueDigest()](/code/functions/projectAlertsOverdueDigest.md), [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)
- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::runSchedules`
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::eventFor`

