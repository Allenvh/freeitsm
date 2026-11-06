# apiAssetSelect()

The SELECT for assets, with type, status, location, company and supplier names, how many people it is assigned to and, once Database Verification has added the column, its cost centre.

```php
function apiAssetSelect(): string
```

Defined in [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), line 42.

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 49 | `apiAssetSelect` | read [cost_centres](/code/tables/cost_centres.md) |  |
| 52 | `apiAssetSelect` | read [users_assets](/code/tables/users_assets.md), read [assets](/code/tables/assets.md), read [asset_types](/code/tables/asset_types.md), read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md), read [tenants](/code/tables/tenants.md), read [suppliers](/code/tables/suppliers.md) |  |

## Calls

[connectToDatabase()](/code/functions/connectToDatabase.md), [costCentreReady()](/code/functions/costCentreReady.md)

## Called from

- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiLoadAsset()](/code/functions/apiLoadAsset.md), [apiAssetsList()](/code/functions/apiAssetsList.md)

