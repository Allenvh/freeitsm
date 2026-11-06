# projectCode()

The reference people say out loud: PRJ-0042.

```php
function projectCode(int $id): string
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 292.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$id` | `int` |  | the projects id |

**Returns:** `string`

## Called from

- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiSerializeProject()](/code/functions/apiSerializeProject.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectEventPayload()](/code/functions/projectEventPayload.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistDates()](/code/functions/projectAssistDates.md)
- [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) - in [projectSyncCalendar()](/code/functions/projectSyncCalendar.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/intake.php](/code/files/includes/projects/intake.php.md) - in [projectCreateFromAction()](/code/functions/projectCreateFromAction.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectDecorate()](/code/functions/projectDecorate.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectsMilestones()](/code/functions/rpProjectsMilestones.md), [rpProjectsRisks()](/code/functions/rpProjectsRisks.md), [rpProjectChoices()](/code/functions/rpProjectChoices.md)
- [includes/services/project_reports.php](/code/files/includes/services/project_reports.php.md) - in `ProjectReportsService::emailHtml`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::lessonToKnowledge`, `ProjectToolsService::issueToTicket`

