# assetLabelPrintFields()

The short names printed ON the label beside each value - space on a 38mm sticker is tight, so "Serial" rather than "Serial / service tag".

```php
function assetLabelPrintFields(PDO $conn, ?int $tenantId = null): array
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 243.

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

