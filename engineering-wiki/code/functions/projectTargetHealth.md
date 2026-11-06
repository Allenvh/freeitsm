# projectTargetHealth()

green | amber | red for a live target; null when there is nothing to measure.

```php
function projectTargetHealth(int $done, int $total, ?string $due, string $setOn): ?string
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 175.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$done` | `int` |  | how many assets in scope are done |
| `$total` | `int` |  | how many assets are in scope; 0 gives null |
| `$due` | `?string` |  | the date it should be done by, 'Y-m-d' (the target's, else the project's); null means no deadline |
| `$setOn` | `string` |  | when the target was set (its created_datetime) - the start of the expected line |

**Returns:** `?string`

## Called from

- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetsFor()](/code/functions/projectTargetsFor.md)

