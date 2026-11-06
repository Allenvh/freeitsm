# projectEarnedValue()

Earned value (3.3.0), from what the budget already holds: BAC budget at completion - the planned total PV planned value by date - the cumulative planned spend (the timeline's planned line) EV earned value by date - BAC x the share of today's whole scope that was done by then: by estimated hours when the project's tasks have estimates, otherwise by count. It only ever rises. AC actual cost by date - the cumulative actual (the timeline's actual line) Today's indices: CPI = EV / AC (above 1 = under budget), SPI = EV / PV (above 1 = ahead), CV = EV - AC, SV = EV - PV, EAC = BAC / CPI, VAC = BAC - EAC. Null when there is no planned budget or no work to measure.

```php
function projectEarnedValue(PDO $conn, int $projectId, array $timeline, float $bac): ?array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 380.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |
| `$timeline` | `array` |  | the spend timeline from `projectBudgetTimeline()` - its points are used as PV and AC |
| `$bac` | `float` |  | budget at completion: the planned total |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 384 | `projectEarnedValue` | read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

## Traps

> [!WARNING]
> **Line 397, in `projectEarnedValue`:** the share is measured against TODAY's whole scope, never the scope as it stood on that date. Measuring against "tasks that existed by then" made earned value FALL whenever work was added (6 of 10 done = 60%, add 10 tasks = 30%), and earned value never falls - the work done is still done. Today's figures are the same either way; only the history changes, and now it can only rise.

