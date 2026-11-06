# projectSettingValidate()

Validate one incoming value; throws InvalidArgumentException with a message fit to show.

```php
function projectSettingValidate(string $key, $raw): string
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 206.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | the setting key; an unknown one throws |
| `$raw` |  |  | the value as sent by the settings form (untrusted) - a string, or an array for scales and weekdays |

**Returns:** `string`

## Calls

[projectMethodologies()](/code/functions/projectMethodologies.md), [projectScaleDefaults()](/code/functions/projectScaleDefaults.md), [projectScaleParse()](/code/functions/projectScaleParse.md), [projectSettingDefinitions()](/code/functions/projectSettingDefinitions.md)

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)

