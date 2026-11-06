# projectScaleDefaults()

The five default words for a scale ('probability' or 'impact') in the viewer's language. They live in lang/<locale>/projects.php, not in the setting, so an install that never changed them reads them in German for a German analyst.

```php
function projectScaleDefaults(string $scale): array
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 364.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$scale` | `string` |  | 'probability', 'impact' or 'priority' |

**Returns:** `array`

## Calls

[projectScaleSize()](/code/functions/projectScaleSize.md), [t()](/code/functions/t.md)

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)
- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectSettingValidate()](/code/functions/projectSettingValidate.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md)

