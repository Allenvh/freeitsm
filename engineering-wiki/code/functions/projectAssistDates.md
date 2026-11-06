# projectAssistDates()

The project dates coming up across every project in `$scope` - stage ends, milestones and target finishes in the next N days (default 14), and milestones already missed. Warbot's "is anything big happening this week?".

```php
function projectAssistDates(PDO $conn, array $scope, array $args): string
```

Defined in [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), line 307.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `array` |  | [sql, args]: the caller's company filter on alias p |
| `$args` | `array` |  | the tool's arguments: days ahead to look (1-90, default 14) |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 314 | `projectAssistDates` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 316 | `projectAssistDates` | read [projects](/code/tables/projects.md) |  |
| 320 | `projectAssistDates` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |
| 322 | `projectAssistDates` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |

## Calls

[projectCode()](/code/functions/projectCode.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md)

## Called from

- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolProjectDates()](/code/functions/mcpToolProjectDates.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolProjectDates()](/code/functions/warbotToolProjectDates.md)

