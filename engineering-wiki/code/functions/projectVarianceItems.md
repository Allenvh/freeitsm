# projectVarianceItems()

Dated items compared by id on one date column: [{name, kind: moved|added|removed, from, to, days}].

```php
function projectVarianceItems(array $base, array $now, string $col): array
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 198.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$base` | `array` |  | the baseline's items (stages or milestones), each with id, name and the date column |
| `$now` | `array` |  | the same items as they are now |
| `$col` | `string` |  | the date column to compare, e.g. 'due_date' or 'end_date' |

**Returns:** `array`

## Calls

[projectDaysBetween()](/code/functions/projectDaysBetween.md)

## Called from

- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectBaselineVariance()](/code/functions/projectBaselineVariance.md)

