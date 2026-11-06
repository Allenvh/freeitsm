# settingGetDecrypted()

One setting, decrypted. See `settingsGetDecrypted()`.

```php
function settingGetDecrypted(PDO $conn, string $key, string $default = ''): string
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 388.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings.setting_key to load |
| `$default` | `string` | `''` | returned when no row exists (a value that will not decrypt still gives '') |

**Returns:** `string`

## Calls

[settingsGetDecrypted()](/code/functions/settingsGetDecrypted.md)

*Nothing calls this function by name.*

