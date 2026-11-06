# apiAssetLocations()

All locations keyed by id — used to build full "UK › London › Office 1" paths.

```php
function apiAssetLocations(PDO $conn): array
```

Defined in [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), line 68.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 72 | `apiAssetLocations` | read [asset_locations](/code/tables/asset_locations.md) |  |

## Called from

- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetLocationPath()](/code/functions/apiAssetLocationPath.md)

