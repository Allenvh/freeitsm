# mcpToolProjectTasks()

MCP tool: a project's tasks.

```php
function mcpToolProjectTasks(PDO $conn, array $args, int $analystId, array $apiKey): string
```

Defined in [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), line 304.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the tool arguments from the client - 'project' names the project |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row, used for the project scope |

**Returns:** `string`

## Calls

[mcpProjectScopeSql()](/code/functions/mcpProjectScopeSql.md), [projectAssistTasks()](/code/functions/projectAssistTasks.md)

*Nothing calls this function by name.*

