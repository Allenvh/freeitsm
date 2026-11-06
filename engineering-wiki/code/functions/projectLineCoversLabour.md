# projectLineCoversLabour()

A labour line with no typed actual is the PLAN for labour: the time logged on the project's tasks is what it is spent on, so the forecast takes the larger of these lines and labour, never both (3.3.0).

```php
function projectLineCoversLabour(array $r): bool
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 260.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$r` | `array` |  | a project_budget_lines row - category and actual_amount are read |

**Returns:** `bool`

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectBudgetTotals()](/code/functions/projectBudgetTotals.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

