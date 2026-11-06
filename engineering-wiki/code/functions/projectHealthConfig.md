# projectHealthConfig()

The Health tab's thresholds, as numbers.

```php
function projectHealthConfig(PDO $conn): array
```

Defined in [includes/projects/read.php](/code/files/includes/projects/read.php.md), line 182.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[projectSetting()](/code/functions/projectSetting.md)

## Called from

- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectDecorate()](/code/functions/apiProjectDecorate.md)
- [includes/people.php](/code/files/includes/people.php.md) - in [peopleProjects()](/code/functions/peopleProjects.md)
- [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md) - in [projectAlertRows()](/code/functions/projectAlertRows.md)
- [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md) - in [projectAssistRows()](/code/functions/projectAssistRows.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkProjectRows()](/code/functions/projectLinkProjectRows.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectListRows()](/code/functions/projectListRows.md), [projectDetail()](/code/functions/projectDetail.md)
- [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md) - in [rpProjectRows()](/code/functions/rpProjectRows.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

