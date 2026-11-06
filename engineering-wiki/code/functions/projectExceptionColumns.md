# projectExceptionColumns()

The columns the exceptions are read from, as SQL - NULLs before Verification.

```php
function projectExceptionColumns(PDO $conn): string
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 206.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 209 | `projectExceptionColumns` | read [project_raid](/code/tables/project_raid.md), read [project_tolerances](/code/tables/project_tolerances.md), read [project_stages](/code/tables/project_stages.md) |  |

## Calls

[projectsPhase2Ready()](/code/functions/projectsPhase2Ready.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectSelect()](/code/functions/apiProjectSelect.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertRows()](/code/functions/projectAlertRows.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistRows()](/code/functions/projectAssistRows.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectListRows()](/code/functions/projectListRows.md), [projectDetail()](/code/functions/projectDetail.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectRows()](/code/functions/rpProjectRows.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

