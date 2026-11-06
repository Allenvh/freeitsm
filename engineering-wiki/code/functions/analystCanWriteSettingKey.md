# analystCanWriteSettingKey()

May this analyst write this setting key?

Once a module converts (phase 3b) its keys name a capability, and that capability is
required. Until then the key falls back to its module's Layer 1 access. is_admin
bypasses both — via `analystHasCapability()` and via analystCanAccessModule('system').

```php
function analystCanWriteSettingKey(PDO $conn, int $analystId, string $key): bool
```

Defined in [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), line 273.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$key` | `string` |  | the system_settings key to be written; a key with no owner is always refused |

**Returns:** `bool`

## Calls

[analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystHasCapability()](/code/functions/analystHasCapability.md), [settingKeyOwner()](/code/functions/settingKeyOwner.md)

## Called from

- [api/knowledge/save_email_settings.php](/code/files/api/knowledge/save_email_settings.php.md)
- [api/settings/save_system_settings.php](/code/files/api/settings/save_system_settings.php.md)
- [api/tasks/save_settings.php](/code/files/api/tasks/save_settings.php.md)
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [analystCanManageAiNamespace()](/code/functions/analystCanManageAiNamespace.md)

