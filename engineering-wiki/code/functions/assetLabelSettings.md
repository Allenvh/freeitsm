# assetLabelSettings()

The label settings for a company: header, footer, which fields in which order, whether to print their names, and the logo inside the QR.

```php
function assetLabelSettings(PDO $conn, ?int $tenantId = null): array
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 258.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{title:string, fields:string[], footer:string, logo_enabled:bool, logo_path:string, custom_logo_path:string, show_field_labels:bool}

## Calls

[assetLabelAvailableFields()](/code/functions/assetLabelAvailableFields.md), [brandingPathIsSafe()](/code/functions/brandingPathIsSafe.md), [tenantSetting()](/code/functions/tenantSetting.md)

## Called from

- [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md)
- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [asset-management/labels.php](/code/files/asset-management/labels.php.md)

