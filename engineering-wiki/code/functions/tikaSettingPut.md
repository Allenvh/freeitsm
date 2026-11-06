# tikaSettingPut()

Write one system_settings row.

```php
function tikaSettingPut(PDO $conn, string $key, string $value): void
```

Defined in [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md), line 29.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the setting_key to write, e.g. TIKA_SETTING_URL |
| `$value` | `string` |  | the value to store |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 | `tikaSettingPut` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## Called from

- [api/system/tika_settings.php](/code/files/api/system/tika_settings.php.md)

