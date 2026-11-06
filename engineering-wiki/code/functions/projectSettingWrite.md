# projectSettingWrite()

Saves one Projects setting to system_settings (insert or update).

```php
function projectSettingWrite(PDO $conn, string $key, string $value): void
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 191.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the setting key, e.g. 'project_currency' |
| `$value` | `string` |  | the value to store, already checked by `projectSettingValidate()` |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 194 | `projectSettingWrite` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`, `updated_datetime`) |  |

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::setBuiltinHidden`

