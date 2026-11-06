# projectDaysBetween()

Whole days from `$a` to `$b` (both Y-m-d); null if either is missing.

```php
function projectDaysBetween(?string $a, ?string $b): ?int
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 140.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$a` | `?string` |  | the earlier date, 'Y-m-d'; null gives null |
| `$b` | `?string` |  | the later date, 'Y-m-d'; null gives null (negative when it is before `$a`) |

**Returns:** `?int`

## Called from

- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectBaselineVariance()](/code/functions/projectBaselineVariance.md), [projectVarianceItems()](/code/functions/projectVarianceItems.md)

