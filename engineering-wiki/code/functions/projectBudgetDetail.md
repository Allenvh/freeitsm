# projectBudgetDetail()

Everything the Budget tab shows.

```php
function projectBudgetDetail(PDO $conn, array $project, int $analystId): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 437.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 441 | `projectBudgetDetail` | read [project_budget_lines](/code/tables/project_budget_lines.md), read [contracts](/code/tables/contracts.md), read [cost_centres](/code/tables/cost_centres.md) |  |

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectBudgetContracts()](/code/functions/projectBudgetContracts.md), [projectBudgetCostCentres()](/code/functions/projectBudgetCostCentres.md), [projectBudgetTimeline()](/code/functions/projectBudgetTimeline.md), [projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectEarnedValue()](/code/functions/projectEarnedValue.md), [projectInstallCurrency()](/code/functions/projectInstallCurrency.md), [projectLabour()](/code/functions/projectLabour.md), [projectLabourRates()](/code/functions/projectLabourRates.md), [projectLabourToCome()](/code/functions/projectLabourToCome.md), [projectLineActual()](/code/functions/projectLineActual.md), [projectLineCoversLabour()](/code/functions/projectLineCoversLabour.md), [projectLineForecast()](/code/functions/projectLineForecast.md), [projectRateOn()](/code/functions/projectRateOn.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectBudget()](/code/functions/apiProjectBudget.md), [apiProjectBudgetLine()](/code/functions/apiProjectBudgetLine.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolProjectBudget()](/code/functions/mcpToolProjectBudget.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)

