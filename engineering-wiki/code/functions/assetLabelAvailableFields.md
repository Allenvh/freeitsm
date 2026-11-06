# assetLabelAvailableFields()

Every field a label can print, with the name the SETTINGS picker shows - built-in fields in the reader's language, custom fields marked as such.

```php
function assetLabelAvailableFields(PDO $conn, ?int $tenantId = null): array
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 228.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[assetLabelCustomFields()](/code/functions/assetLabelCustomFields.md), [t()](/code/functions/t.md)

## Called from

- [api/assets/get_asset_label_settings.php](/code/files/api/assets/get_asset_label_settings.php.md)
- [api/assets/save_asset_label_settings.php](/code/files/api/assets/save_asset_label_settings.php.md)
- [asset-management/labels.php](/code/files/asset-management/labels.php.md)
- [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) - in [assetLabelSettings()](/code/functions/assetLabelSettings.md)

