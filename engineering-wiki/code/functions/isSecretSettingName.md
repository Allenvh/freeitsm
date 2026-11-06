# isSecretSettingName()

Is this key named like a credential (*_password, *_secret, *_token, *_api_key)? The rule `isEncryptedSettingKey()` falls back on, shared so a reader can refuse to send such a key in the clear (api/settings/get_system_settings.php).

```php
function isSecretSettingName($key)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 205.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` |  |  | a system_settings.setting_key; cast to string, matched case-insensitively |

## Called from

- [api/settings/get_system_settings.php](/code/files/api/settings/get_system_settings.php.md)
- [includes/encryption.php](/code/files/includes/encryption.php.md) - in [isEncryptedSettingKey()](/code/functions/isEncryptedSettingKey.md)

