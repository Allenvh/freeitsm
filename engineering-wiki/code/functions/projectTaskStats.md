# projectTaskStats()

Per-project task counts in one query: total, done, overdue (top-level tasks only), plus targets_health - the worst of the project's asset targets (includes/projects/targets.php) - so every screen's health agrees.

```php
function projectTaskStats(PDO $conn, array $projectIds): array
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectIds` | `array` |  | the project ids to count for |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 | `projectTaskStats` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 54 | `projectTaskStats` | read [project_tickets](/code/tables/project_tickets.md), read [tickets](/code/tables/tickets.md) |  |
| 68 | `projectTaskStats` | read [projects](/code/tables/projects.md) |  |
| 84 | `projectTaskStats` | read [project_raid](/code/tables/project_raid.md) |  |

## Calls

[projectBenefitStats()](/code/functions/projectBenefitStats.md), [projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectChangeStats()](/code/functions/projectChangeStats.md), [projectMilestoneStats()](/code/functions/projectMilestoneStats.md), [projectTargetsFor()](/code/functions/projectTargetsFor.md), [projectTargetsWorst()](/code/functions/projectTargetsWorst.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectDecorate()](/code/functions/apiProjectDecorate.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleProjects()](/code/functions/peopleProjects.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertRows()](/code/functions/projectAlertRows.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistRows()](/code/functions/projectAssistRows.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkProjectRows()](/code/functions/projectLinkProjectRows.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectListRows()](/code/functions/projectListRows.md), [projectDetail()](/code/functions/projectDetail.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectRows()](/code/functions/rpProjectRows.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

