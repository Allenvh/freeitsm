# projectAssistOverview()

One project in full. `$withBudget`: the money line - the MCP server gives it to the key's analyst; Warbot leaves it out, because its answer is read by everyone in the channel.

```php
function projectAssistOverview(
    PDO $conn,
    array $scope,
    int $analystId,
    array $args,
    bool $withBudget
): string
```

Defined in [includes/projects/assistant.php](/code/files/includes/projects/assistant.php.md), line 171.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$scope` | `array` |  | [sql, args]: the caller's company filter on alias p |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the tool's arguments: project (code, id or name) |
| `$withBudget` | `bool` |  | true to include the money line and budget history (MCP); false for Warbot, read by a whole channel |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 183 | `projectAssistOverview` | read [project_stages](/code/tables/project_stages.md) |  |
| 215 | `projectAssistOverview` | read [project_audit](/code/tables/project_audit.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectAssistFind()](/code/functions/projectAssistFind.md), [projectAssistLine()](/code/functions/projectAssistLine.md), [projectAssistMoney()](/code/functions/projectAssistMoney.md), [projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectLinkKindAllowed()](/code/functions/projectLinkKindAllowed.md), [projectMilestones()](/code/functions/projectMilestones.md), [projectUnapprovedChanges()](/code/functions/projectUnapprovedChanges.md)

## Called from

- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolProjectOverview()](/code/functions/mcpToolProjectOverview.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolProjectOverview()](/code/functions/warbotToolProjectOverview.md)

