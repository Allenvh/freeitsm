# assetLocationsSharedReady()

Has Database Verification added asset_locations.is_shared yet?

```php
function assetLocationsSharedReady(PDO $conn): bool
```

Defined in [includes/asset_locations.php](/code/files/includes/asset_locations.php.md), line 34.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

## Called from

- [api/assets/get_asset_locations.php](/code/files/api/assets/get_asset_locations.php.md)
- [api/assets/save_asset_location.php](/code/files/api/assets/save_asset_location.php.md)
- [includes/asset_locations.php](/code/files/includes/asset_locations.php.md) - in [assetLocationScope()](/code/functions/assetLocationScope.md), [assetLocationIsShared()](/code/functions/assetLocationIsShared.md)

