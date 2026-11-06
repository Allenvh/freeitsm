# maskSecret()

Produce a "****<last4>" mask. Empty input returns ''.

```php
function maskSecret($value)
```

Defined in [includes/encryption.php](/code/files/includes/encryption.php.md), line 103.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$value` |  |  | the plaintext secret (string or null); under 4 characters gives just "****" |

## Called from

- [api/cmdb/get_ai_settings.php](/code/files/api/cmdb/get_ai_settings.php.md)
- [api/knowledge/get_email_settings.php](/code/files/api/knowledge/get_email_settings.php.md)
- [api/rfp-builder/get_ai_settings.php](/code/files/api/rfp-builder/get_ai_settings.php.md)
- [api/settings/get_system_settings.php](/code/files/api/settings/get_system_settings.php.md)
- [api/tickets/get_reply_cleanup_settings.php](/code/files/api/tickets/get_reply_cleanup_settings.php.md)
- [includes/ai_settings.php](/code/files/includes/ai_settings.php.md) - in [aiSettingsForUi()](/code/functions/aiSettingsForUi.md)

