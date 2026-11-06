# aiSettingsRegistry()

Allowlisted AI config namespaces. Adding a module = one line here + dropping renderAiSettingsPanel('<ns>') on its settings page. The <ns>_api_key MUST also be added to ENCRYPTED_SETTING_KEYS + MASKED_SETTING_KEYS in encryption.php.

```php
function aiSettingsRegistry(): array
```

Defined in [includes/ai_settings.php](/code/files/includes/ai_settings.php.md), line 30.

**Returns:** `array`

## Called from

- [api/system/debug-tools/D014_ai_connections.php](/code/files/api/system/debug-tools/D014_ai_connections.php.md)
- [includes/ai_settings.php](/code/files/includes/ai_settings.php.md) - in [aiSettingsIsValidNs()](/code/functions/aiSettingsIsValidNs.md), [aiSettingsEntry()](/code/functions/aiSettingsEntry.md)
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [settingKeyOwners()](/code/functions/settingKeyOwners.md), [analystCanManageAnyAiNamespace()](/code/functions/analystCanManageAnyAiNamespace.md)
- [system/ai/index.php](/code/files/system/ai/index.php.md)
- [system/debug-tools/includes/tools.php](/code/files/system/debug-tools/includes/tools.php.md) - in [debugToolAiFeatureOptions()](/code/functions/debugToolAiFeatureOptions.md)

