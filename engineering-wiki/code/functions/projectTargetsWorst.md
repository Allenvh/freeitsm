# projectTargetsWorst()

The worst health across a project's targets, or null.

```php
function projectTargetsWorst(array $targets): ?string
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 229.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$targets` | `array` |  | one project's targets as `projectTargetsFor()` gives them, each with health |

**Returns:** `?string`

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectTaskStats()](/code/functions/projectTaskStats.md)

