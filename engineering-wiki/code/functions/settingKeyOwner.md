# settingKeyOwner()

Who owns this setting key? null = nobody, so it may not be written through the generic endpoint. Exact matches win over prefixes.

```php
function settingKeyOwner(string $key): ?array
```

Defined in [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), line 194.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$key` | `string` |  | a system_settings key, e.g. 'vcenter_server' or 'module_color_tickets' |

**Returns:** array{module:string,cap:?string,tab:string}|null

## Calls

[settingKeyOwners()](/code/functions/settingKeyOwners.md), [settingKeyPrefixes()](/code/functions/settingKeyPrefixes.md)

## Called from

- [api/settings/save_system_settings.php](/code/files/api/settings/save_system_settings.php.md)
- [api/tasks/save_settings.php](/code/files/api/tasks/save_settings.php.md)
- [includes/capabilities.php](/code/files/includes/capabilities.php.md) - in [capSelfCheck()](/code/functions/capSelfCheck.md)
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [analystCanManageAiNamespace()](/code/functions/analystCanManageAiNamespace.md), [analystCanWriteSettingKey()](/code/functions/analystCanWriteSettingKey.md)

