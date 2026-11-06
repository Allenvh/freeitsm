# tenantSetting()

The value of a setting for one company.

```php
function tenantSetting(PDO $conn, ?int $tenantId, string $key, ?string $default = null): ?string
```

Defined in [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | null = ask the install-wide value only |
| `$key` | `string` |  | the setting key, the same name in tenant_settings and system_settings |
| `$default` | `?string` | `null` | returned when neither the company nor the install has a value |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `tenantSetting` | read [tenant_settings](/code/tables/tenant_settings.md) |  |
| 63 | `tenantSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md), [tenancyTablesReady()](/code/functions/tenancyTablesReady.md)

## Called from

- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) - in [assetLabelSettings()](/code/functions/assetLabelSettings.md)
- [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md) - in `AssetTagsService::config`
- [includes/services/mandatory_fields.php](/code/files/includes/services/mandatory_fields.php.md) - in `MandatoryFieldsService::settings`
- [includes/tenant_settings.php](/code/files/includes/tenant_settings.php.md) - in [tenantSettingOn()](/code/functions/tenantSettingOn.md), [ticketChecklistClosureMode()](/code/functions/ticketChecklistClosureMode.md), [ticketChecklistEmptyClosureMode()](/code/functions/ticketChecklistEmptyClosureMode.md)

