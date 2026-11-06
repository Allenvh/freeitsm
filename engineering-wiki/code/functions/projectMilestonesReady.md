# projectMilestonesReady()

Has Database Verification created the table? Everything here is quiet before it.

```php
function projectMilestonesReady(PDO $conn): bool
```

Defined in [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md), line 22.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `projectMilestonesReady` | read [project_milestones](/code/tables/project_milestones.md) |  |

## Called from

- [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertsMilestones()](/code/functions/projectAlertsMilestones.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistDates()](/code/functions/projectAssistDates.md)
- [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) - in [projectSyncCalendar()](/code/functions/projectSyncCalendar.md)
- [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md) - in [projectMilestones()](/code/functions/projectMilestones.md), [projectMilestoneStats()](/code/functions/projectMilestoneStats.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectsMilestones()](/code/functions/rpProjectsMilestones.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveMilestone`, `ProjectToolsService::milestone`

