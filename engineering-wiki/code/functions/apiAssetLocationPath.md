# apiAssetLocationPath()

The full path of a location, top level first, or null when there is no location.

```php
function apiAssetLocationPath(PDO $conn, ?int $locationId): ?string
```

Defined in [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), line 84.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$locationId` | `?int` |  | the asset_locations id; null gives null |

**Returns:** `?string`

## Calls

[apiAssetLocations()](/code/functions/apiAssetLocations.md)

## Called from

- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiSerializeAsset()](/code/functions/apiSerializeAsset.md), [apiAssetLocationsList()](/code/functions/apiAssetLocationsList.md)

