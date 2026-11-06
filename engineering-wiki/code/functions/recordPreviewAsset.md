# recordPreviewAsset()

── Asset ───────────────────────────────────────────────────────────────────

```php
function recordPreviewAsset(PDO $conn, int $analystId, int $id): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 299.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the assets id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 306 | `recordPreviewAsset` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [analysts](/code/tables/analysts.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md) |  |
| 338 | `recordPreviewAsset` | read [assets](/code/tables/assets.md) |  |

## Calls

[analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md), [rpField()](/code/functions/rpField.md), [rpFields()](/code/functions/rpFields.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

