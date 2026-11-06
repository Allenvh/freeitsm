# isMaskedSettingKey()

Check if a system_settings key should be masked when returned to the client.

```php
function isMaskedSettingKey($key)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` |  |  | a system_settings.setting_key, matched exactly against MASKED_SETTING_KEYS |

## Called from

- [api/rfp-builder/get_ai_settings.php](/code/files/api/rfp-builder/get_ai_settings.php.md)
- [api/settings/get_system_settings.php](/code/files/api/settings/get_system_settings.php.md)
- [api/settings/save_system_settings.php](/code/files/api/settings/save_system_settings.php.md)

