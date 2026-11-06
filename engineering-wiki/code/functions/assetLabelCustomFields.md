# assetLabelCustomFields()

Custom asset fields a label can print: key ('cf_' + field_key) => its own label.

```php
function assetLabelCustomFields(PDO $conn, ?int $tenantId = null): array
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 206.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` | `null` | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 209 | `assetLabelCustomFields` | read [asset_fields](/code/tables/asset_fields.md) |  |

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) - in [assetLabelAvailableFields()](/code/functions/assetLabelAvailableFields.md), [assetLabelPrintFields()](/code/functions/assetLabelPrintFields.md)

