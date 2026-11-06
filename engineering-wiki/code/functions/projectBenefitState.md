# projectBenefitState()

The worked-out state and progress of one benefit, given its latest value.

```php
function projectBenefitState(array $b, ?float $now, ?string $today = null): array
```

Defined in [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md), line 51.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$b` | `array` |  | a project_benefits row - baseline_value, target_value, direction, status, target_date, review_date |
| `$now` | `?float` |  | the latest measured value; null when it has never been measured |
| `$today` | `?string` | `null` | the date to judge against as 'Y-m-d'; null means today (UTC) |

**Returns:** `array`

## Called from

- [includes/projects/benefits.php](/code/files/includes/projects/benefits.php.md) - in [projectBenefits()](/code/functions/projectBenefits.md)

