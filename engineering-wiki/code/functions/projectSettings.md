# projectSettings()

Every setting with its default applied. Cached; `$fresh` after a save.

```php
function projectSettings(PDO $conn, bool $fresh = false): array
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 155.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$fresh` | `bool` | `false` | true to re-read from the database (after a save) instead of the per-request cache |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 164 | `projectSettings` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[projectSettingDefinitions()](/code/functions/projectSettingDefinitions.md)

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md) - in [projectSettingsForScreen()](/code/functions/projectSettingsForScreen.md)
- [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) - in [projectSyncCalendar()](/code/functions/projectSyncCalendar.md)
- [includes/projects/capacity.php](/code/files/includes/projects/capacity.php.md) - in [projectCapacity()](/code/functions/projectCapacity.md)
- [includes/projects/settings.php](/code/files/includes/projects/settings.php.md) - in [projectSetting()](/code/functions/projectSetting.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::setBuiltinHidden`

