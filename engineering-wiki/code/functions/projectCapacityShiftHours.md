# projectCapacityShiftHours()

Length of a rota shift in hours; a shift that ends at or before it starts runs past midnight.

```php
function projectCapacityShiftHours(string $start, string $end): float
```

Defined in [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$start` | `string` |  | the shift's start time of day, e.g. '09:00' |
| `$end` | `string` |  | the shift's end time of day; at or before `$start` means it ends the next day |

**Returns:** `float`

## Called from

- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)

