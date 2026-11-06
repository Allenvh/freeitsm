# settingKeyOwners()

Exact key -> owner. 'tab' is documentation: it names the settings tab the key belongs to, so the eventual per-tab capability is obvious when the module converts.

```php
function settingKeyOwners(): array
```

Defined in [includes/settings_keys.php](/code/files/includes/settings_keys.php.md), line 58.

**Returns:** array<string,array{module:string,cap:?string,tab:string}>

## Calls

[aiSettingsRegistry()](/code/functions/aiSettingsRegistry.md), [settingsManifests()](/code/functions/settingsManifests.md)

## Called from

- [api/system/debug-tools/D005_endpoint_permissions.php](/code/files/api/system/debug-tools/D005_endpoint_permissions.php.md)
- [includes/settings_keys.php](/code/files/includes/settings_keys.php.md) - in [settingKeyOwner()](/code/functions/settingKeyOwner.md)

