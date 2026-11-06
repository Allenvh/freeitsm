# portalProfileSettingRaw()

A raw system_settings value, or null when it has never been saved.

```php
function portalProfileSettingRaw(PDO $conn, string $key): ?string
```

Defined in [includes/users.php](/code/files/includes/users.php.md), line 263.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings key, e.g. PORTAL_PROFILE_FIELDS_SETTING |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 266 | `portalProfileSettingRaw` | read [system_settings](/code/tables/system_settings.md) |  |

## Called from

- [includes/users.php](/code/files/includes/users.php.md) - in [portalProfileEditableFields()](/code/functions/portalProfileEditableFields.md), [portalProfileAddressBookWrites()](/code/functions/portalProfileAddressBookWrites.md)

