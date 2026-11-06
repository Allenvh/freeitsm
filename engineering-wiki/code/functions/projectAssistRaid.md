# projectAssistRaid()

args: project, type (risk | assumption | issue | dependency | decision | lesson), status (open | closed | all).

```php
function projectAssistRaid(PDO $conn, array $scope, array $args): string
```

Defined in [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), line 246.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `array` |  | [sql, args]: the caller's company filter on alias p |
| `$args` | `array` |  | the tool's arguments: project, type (risk, issue, ...; '' for all), status (open, closed or all; default open) |

**Returns:** `string`

## Calls

[projectAssistFind()](/code/functions/projectAssistFind.md)

## Called from

- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolProjectRaid()](/code/functions/mcpToolProjectRaid.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolProjectRaid()](/code/functions/warbotToolProjectRaid.md)

