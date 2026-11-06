# projectBaselineVariance()

How the plan NOW differs from a baseline. Headline figures, then the detail by item: moved (days), added (in the plan now, not in the baseline) and removed (in the baseline, gone now). Items are matched by id, so a renamed milestone is the same milestone.

```php
function projectBaselineVariance(array $base, array $now): array
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 155.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$base` | `array` |  | the baseline in the snapshot shape (projectBaselineShape()) |
| `$now` | `array` |  | the plan as it is now (projectPlanSnapshot()) |

**Returns:** `array`

## Calls

[projectDaysBetween()](/code/functions/projectDaysBetween.md), [projectVarianceItems()](/code/functions/projectVarianceItems.md)

## Called from

- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectControlDetail()](/code/functions/projectControlDetail.md)

