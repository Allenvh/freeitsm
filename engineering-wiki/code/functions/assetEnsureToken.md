# assetEnsureToken()

The token for an asset, minting one if it has never been labelled.

Minting on demand rather than at creation keeps the column empty for the
thousands of auto-discovered assets nobody will ever print a label for, and
means an asset's token comes into existence at the moment it acquires meaning.

```php
function assetEnsureToken(PDO $conn, int $assetId): ?string
```

Defined in [includes/asset_labels.php](/code/files/includes/asset_labels.php.md), line 69.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 72 | `assetEnsureToken` | read [assets](/code/tables/assets.md) |  |
| 83 | `assetEnsureToken` | **update** [assets](/code/tables/assets.md) (`qr_token`) |  |

## Calls

[assetLabelsSchemaReady()](/code/functions/assetLabelsSchemaReady.md)

## Called from

- [asset-management/labels.php](/code/files/asset-management/labels.php.md)

