# projectCapacityWorkdays()

The working days from `$from` to `$to` inclusive, as Y-m-d. `$days`: ISO weekdays (1 = Monday), Monday to Friday by default.

```php
function projectCapacityWorkdays(string $from, string $to, array $days = [1, 2, 3, 4, 5]): array
```

Defined in [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$from` | `string` |  | the first day, 'Y-m-d' |
| `$to` | `string` |  | the last day, 'Y-m-d' (included) |
| `$days` | `array` | `[1, 2, 3, 4, 5]` | the working weekdays as ISO numbers, 1 = Monday to 7 = Sunday |

**Returns:** `array`

## Called from

- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)

