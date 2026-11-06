# projectScaleSize()

How many steps a scale has: five for the RAID scales, four for priority (3.3.0).

```php
function projectScaleSize(string $scale): int
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 398.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$scale` | `string` |  | 'probability', 'impact' or 'priority' |

**Returns:** `int`

## Called from

- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectScaleDefaults()](/code/functions/projectScaleDefaults.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md)

