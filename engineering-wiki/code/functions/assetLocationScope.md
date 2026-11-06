# assetLocationScope()

The locations one company may see: its own plus every shared one.

```php
function assetLocationScope(PDO $conn, int $tenantId, string $alias = 'l'): array
```

Defined in [includes/asset_locations.php](/code/files/includes/asset_locations.php.md), line 44.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` | `'l'` | the asset_locations alias ('' for none) |

**Returns:** array [sqlFragment, params] to append to a WHERE

## Calls

[assetLocationsSharedReady()](/code/functions/assetLocationsSharedReady.md), [tenantScopeSqlFor()](/code/functions/tenantScopeSqlFor.md)

## Called from

- [api/assets/create_asset.php](/code/files/api/assets/create_asset.php.md)
- [api/assets/get_asset_locations.php](/code/files/api/assets/get_asset_locations.php.md)
- [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::moveToCompany`

