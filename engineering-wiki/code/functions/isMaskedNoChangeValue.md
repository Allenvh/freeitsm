# isMaskedNoChangeValue()

Treat a save-time value as "leave existing untouched" when it's empty, null, or starts with one or more asterisks (i.e. the user submitted the masked placeholder unchanged).

```php
function isMaskedNoChangeValue($value)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 116.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the value submitted from a settings form (string or null) |

## Called from

- [api/forms/save_ai_settings.php](/code/files/api/forms/save_ai_settings.php.md)
- [api/forms/test_ai_key.php](/code/files/api/forms/test_ai_key.php.md)
- [api/knowledge/save_email_settings.php](/code/files/api/knowledge/save_email_settings.php.md)
- [api/rfp-builder/save_ai_settings.php](/code/files/api/rfp-builder/save_ai_settings.php.md)
- [api/rfp-builder/test_ai_connection.php](/code/files/api/rfp-builder/test_ai_connection.php.md)
- [api/settings/save_system_settings.php](/code/files/api/settings/save_system_settings.php.md)
- [api/system/calendar_sync.php](/code/files/api/system/calendar_sync.php.md)
- [api/system/save_sso_provider.php](/code/files/api/system/save_sso_provider.php.md)
- [api/system/test_carddav_connection.php](/code/files/api/system/test_carddav_connection.php.md)
- [api/system/test_carddav_write.php](/code/files/api/system/test_carddav_write.php.md)
- [api/system/test_ldap_connection.php](/code/files/api/system/test_ldap_connection.php.md)
- [api/workflow/save_ai_settings.php](/code/files/api/workflow/save_ai_settings.php.md)
- [api/workflow/test_ai_key.php](/code/files/api/workflow/test_ai_key.php.md)
- [includes/ai_settings.php](/code/files/includes/ai_settings.php.md) - in [aiSettingsSave()](/code/functions/aiSettingsSave.md)

