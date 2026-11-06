# projectLabourRates()

Every rate, [scope][ref] => [[from, rate], ...] newest first. Cached per request.

```php
function projectLabourRates(PDO $conn): array
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 92.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 97 | `projectLabourRates` | read [project_labour_rates](/code/tables/project_labour_rates.md) |  |

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectLabour()](/code/functions/projectLabour.md), [projectLabourToCome()](/code/functions/projectLabourToCome.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

