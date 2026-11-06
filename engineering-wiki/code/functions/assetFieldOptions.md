# assetFieldOptions()

Dropdown options for a field, memoised for the life of the request.

```php
function assetFieldOptions(PDO $conn, int $fieldId): array
```

Defined in [api/assets/get_asset_custom_fields.php](/code/files/api/assets/get_asset_custom_fields.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$fieldId` | `int` |  | the asset_custom_fields id whose options are wanted |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 | `assetFieldOptions` | read [asset_field_options](/code/tables/asset_field_options.md) |  |

## Called from

- [api/assets/get_asset_custom_fields.php](/code/files/api/assets/get_asset_custom_fields.php.md)

