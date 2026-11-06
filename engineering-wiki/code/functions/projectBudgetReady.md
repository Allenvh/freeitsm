# projectBudgetReady()

True when the budget tables and the projects.currency column exist (after Database Verification). Cached per request.

```php
function projectBudgetReady(PDO $conn): bool
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 57.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 61 | `projectBudgetReady` | read [project_budget_lines](/code/tables/project_budget_lines.md) |  |
| 61 | `projectBudgetReady` | read [project_labour_rates](/code/tables/project_labour_rates.md) |  |
| 61 | `projectBudgetReady` | read [projects](/code/tables/projects.md) |  |

## Called from

- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectBudget()](/code/functions/apiProjectBudget.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolProjectBudget()](/code/functions/mcpToolProjectBudget.md)
- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetTotals()](/code/functions/projectBudgetTotals.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectTaskStats()](/code/functions/projectTaskStats.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::decideChangeRequest`, `ProjectToolsService::saveBudgetLine`

