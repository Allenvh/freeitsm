# projectSettingsForScreen()

Settings as the screen shows them: each risk scale as its five words.

```php
function projectSettingsForScreen(PDO $conn): array
```

Defined in [api/projects/settings.php](/code/files/api/projects/settings.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[projectScaleLabels()](/code/functions/projectScaleLabels.md), [projectSettings()](/code/functions/projectSettings.md)

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)

