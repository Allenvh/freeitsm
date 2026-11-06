# projectBudgetTimeline()

Spend over time for the chart (3.3.0): cumulative planned and actual by day, with the forecast from today to the target finish and the lines to measure against. Undated planned money counts from the start; undated actual money from the day its line was last saved; labour on the day it was logged.

```php
function projectBudgetTimeline(
    PDO $conn,
    array $project,
    array $lineRows,
    array $labourDays,
    float $planned,
    float $actual,
    float $forecast
): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 330.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - start_date, created_datetime and target_end_date are read |
| `$lineRows` | `array` |  | one entry per budget line: planned, actual, currency_mismatch, planned_date, spent_date, saved |
| `$labourDays` | `array` |  | labour cost by day, date => amount, from projectLabour(..., true) |
| `$planned` | `float` |  | the planned total |
| `$actual` | `float` |  | the actual total, labour included |
| `$forecast` | `float` |  | the forecast total (estimate at completion) |

**Returns:** `array`

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

