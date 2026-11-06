# tenantSettingForget()

Forget every cached setting - after a write, or between test cases.

```php
function tenantSettingForget(): void
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 90.

**Returns:** `void`

## Called from

- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md) - in `AssetTagsService::forget`
- [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) - in [setTenantSetting()](/code/functions/setTenantSetting.md)

