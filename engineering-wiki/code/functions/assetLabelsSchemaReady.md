# assetLabelsSchemaReady()

Does this database have the label columns yet? Cached per request.

```php
function assetLabelsSchemaReady(PDO $conn): bool
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 50.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 | `assetLabelsSchemaReady` | read [assets](/code/tables/assets.md) |  |

## Called from

- [api/assets/find_asset.php](/code/files/api/assets/find_asset.php.md)
- [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md)
- [api/assets/resolve_scan.php](/code/files/api/assets/resolve_scan.php.md)
- [api/assets/save_asset_tag.php](/code/files/api/assets/save_asset_tag.php.md)
- [asset-management/assign-tags.php](/code/files/asset-management/assign-tags.php.md)
- [asset-management/labels.php](/code/files/asset-management/labels.php.md)
- [asset-management/scan.php](/code/files/asset-management/scan.php.md)
- [asset-management/scanner.php](/code/files/asset-management/scanner.php.md)
- [includes/asset_labels.php](/code/files/includes/asset_labels.php.md) - in [assetEnsureToken()](/code/functions/assetEnsureToken.md), [assetIdForToken()](/code/functions/assetIdForToken.md), [assetTagAvailable()](/code/functions/assetTagAvailable.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewAsset()](/code/functions/recordPreviewAsset.md)

