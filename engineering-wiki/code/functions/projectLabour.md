# projectLabour()

Labour for projects: time logged on their tasks, and what it cost.

```php
function projectLabour(PDO $conn, array $projects, bool $byDay = false): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 132.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projects` | `array` |  | id => row (needs id, currency) |
| `$byDay` | `bool` | `false` | true to add 'days' => [date => cost] to each project, for the spend chart |

**Returns:** array id => [minutes, priced_minutes, cost (null in hours mode), unpriced_minutes]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 142 | `projectLabour` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md) |  |

## Calls

[projectCurrencyOf()](/code/functions/projectCurrencyOf.md), [projectInstallCurrency()](/code/functions/projectInstallCurrency.md), [projectLabourRates()](/code/functions/projectLabourRates.md), [projectRateOn()](/code/functions/projectRateOn.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

