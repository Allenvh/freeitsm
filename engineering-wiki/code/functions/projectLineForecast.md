# projectLineForecast()

A line's forecast: what was typed, else the larger of planned and actual; null when it has neither (3.3.0).

```php
function projectLineForecast(array $r, ?float $actual): ?float
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 245.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a project_budget_lines row - forecast_amount and planned_amount are read |
| `$actual` | `?float` |  | the line's actual from `projectLineActual()`; null when it has none |

**Returns:** `?float`

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

