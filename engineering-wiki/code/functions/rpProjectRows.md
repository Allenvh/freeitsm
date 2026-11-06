# rpProjectRows()

Projects in the pack's company, decorated with health and progress.

```php
function rpProjectRows(PDO $conn, int $analystId, $tenant, array $ids, bool $liveOnly): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |
| `$ids` | `array` |  | project ids to narrow to; [] means every project |
| `$liveOnly` | `bool` |  | true for proposed and active projects only; false for all but cancelled |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `rpProjectRows` | read [project_stages](/code/tables/project_stages.md) |  |
| 47 | `rpProjectRows` | read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectDecorate()](/code/functions/projectDecorate.md), [projectExceptionColumns()](/code/functions/projectExceptionColumns.md), [projectHealthConfig()](/code/functions/projectHealthConfig.md), [projectTaskStats()](/code/functions/projectTaskStats.md), [rpProjectClause()](/code/functions/rpProjectClause.md)

## Called from

- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectsKpis()](/code/functions/rpProjectsKpis.md), [rpProjectsHealth()](/code/functions/rpProjectsHealth.md), [rpProjectsStatus()](/code/functions/rpProjectsStatus.md)

