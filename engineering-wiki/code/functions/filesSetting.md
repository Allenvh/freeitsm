# filesSetting()

Reads a Files setting from system_settings, cached per request; blank or missing gives `$default`.

```php
function filesSetting(PDO $conn, string $key, string $default = ''): string
```

Defined in [includes/files/storage.php](/code/files/includes/files/storage.php.md), line 30.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings.setting_key, e.g. 'files_storage_root' or 'files_max_upload_mb' |
| `$default` | `string` | `''` | what to return when the setting is missing or blank |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 | `filesSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [api/files/settings.php](/code/files/api/files/settings.php.md)
- [includes/files/storage.php](/code/files/includes/files/storage.php.md) - in [filesStorageRoot()](/code/functions/filesStorageRoot.md), [filesMaxUploadBytes()](/code/functions/filesMaxUploadBytes.md), [filesDesktopLogo()](/code/functions/filesDesktopLogo.md)

