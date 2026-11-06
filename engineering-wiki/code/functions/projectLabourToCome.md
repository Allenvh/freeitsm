# projectLabourToCome()

Labour still to come (3.3.0): the hours left on open tasks' estimates - each task's estimate less the time logged on that task - priced at the rate in force today, the same rules as `projectLabour()`. In hours mode, or where no rate applies, the hours are counted and the cost is left out.

```php
function projectLabourToCome(PDO $conn, array $projects): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 274.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projects` | `array` |  | id => projects row (needs currency) |

**Returns:** array id => [hours, cost|null, unpriced_hours, open_unestimated]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 285 | `projectLabourToCome` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |

## Calls

[projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectInstallCurrency()](/code/functions/projectInstallCurrency.md), [projectLabourRates()](/code/functions/projectLabourRates.md), [projectRateOn()](/code/functions/projectRateOn.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

