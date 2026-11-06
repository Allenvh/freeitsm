# contractAssetCanReach()

Can this analyst reach this asset at all?

The gate for every write. Returns false for an asset that does not exist,
which is the same answer as one they may not see — deliberately, because
telling them apart tells you a row exists.

```php
function contractAssetCanReach(PDO $conn, int $analystId, int $assetId): bool
```

Defined in [includes/contract_assets.php](/code/files/includes/contract_assets.php.md), line 41.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$assetId` | `int` |  | The id of the asset. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `contractAssetCanReach` | read [assets](/code/tables/assets.md) |  |

## Calls

[contractAssetReachSql()](/code/functions/contractAssetReachSql.md)

## Called from

- [api/assets/get_asset_contracts.php](/code/files/api/assets/get_asset_contracts.php.md)
- [api/assets/search_linkable_contracts.php](/code/files/api/assets/search_linkable_contracts.php.md)
- [includes/contract_assets.php](/code/files/includes/contract_assets.php.md) - in [contractAssetLink()](/code/functions/contractAssetLink.md), [contractAssetLoad()](/code/functions/contractAssetLoad.md)

