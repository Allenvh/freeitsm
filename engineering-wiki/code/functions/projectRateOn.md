# projectRateOn()

The rate in force on `$date` for one scope/ref, or null.

```php
function projectRateOn(array $rates, string $scope, int $ref, string $date): ?float
```

Defined in [includes/projects/budget.php](/code/files/includes/projects/budget.php.md), line 118.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rates` | `array` |  | every rate, as `projectLabourRates()` returns it: [scope][ref] => [[from, rate], ...] newest first |
| `$scope` | `string` |  | 'default', 'project' or 'analyst' |
| `$ref` | `int` |  | the project or analyst id the rate belongs to; 0 for the default rate |
| `$date` | `string` |  | the day to price, 'Y-m-d' |

**Returns:** `?float`

## Called from

- [includes/projects/budget.php](/code/files/includes/projects/budget.php.md) - in [projectLabour()](/code/functions/projectLabour.md), [projectLabourToCome()](/code/functions/projectLabourToCome.md), [projectBudgetDetail()](/code/functions/projectBudgetDetail.md)

