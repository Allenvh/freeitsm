# mcpProjectScopeSql()

" AND p.tenant_id ..." for the key's companies (NULL company = the Default one).

```php
function mcpProjectScopeSql(PDO $conn, array $apiKey): array
```

Defined in [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), line 234.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | the authenticated api_keys row (analyst_id, narrowed company_scope) |

**Returns:** `array`

## Calls

[mcpProjectCompanySql()](/code/functions/mcpProjectCompanySql.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md)

## Called from

- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolListProjects()](/code/functions/mcpToolListProjects.md), [mcpToolProjectOverview()](/code/functions/mcpToolProjectOverview.md), [mcpToolProjectRaid()](/code/functions/mcpToolProjectRaid.md), [mcpToolProjectTasks()](/code/functions/mcpToolProjectTasks.md), [mcpToolProjectDates()](/code/functions/mcpToolProjectDates.md), [mcpToolProjectBudget()](/code/functions/mcpToolProjectBudget.md)

