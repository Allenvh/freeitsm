# projectBudgetTotals()

Budget totals for many projects at once - for health, exceptions, reports.

```php
function projectBudgetTotals(PDO $conn, array $projects): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 179.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projects` | `array` |  | id => row (needs currency) |

**Returns:** array id => [planned, actual, currency, has_budget]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 184 | `projectBudgetTotals` | read [project_budget_lines](/code/tables/project_budget_lines.md), read [contracts](/code/tables/contracts.md) |  |

## Calls

[projectBudgetReady()](/code/functions/projectBudgetReady.md), [projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectLabour()](/code/functions/projectLabour.md), [projectLabourToCome()](/code/functions/projectLabourToCome.md), [projectLineActual()](/code/functions/projectLineActual.md), [projectLineCoversLabour()](/code/functions/projectLineCoversLabour.md), [projectLineForecast()](/code/functions/projectLineForecast.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [api/projects/export.php](/code/files/api/projects/export.php.md)
- [api/projects/portfolio_charts.php](/code/files/api/projects/portfolio_charts.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectTaskStats()](/code/functions/projectTaskStats.md)

