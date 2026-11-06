# filesSaveSetting()

Saves one Files setting to system_settings (insert or update).

```php
function filesSaveSetting(PDO $conn, string $key, ?string $value): void
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings.setting_key, e.g. 'files_storage_root' |
| `$value` | `?string` |  | the value to store; null stores NULL, which `filesSetting()` reads as unset |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `filesSaveSetting` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## Called from

- [api/files/settings.php](/code/files/api/files/settings.php.md)

