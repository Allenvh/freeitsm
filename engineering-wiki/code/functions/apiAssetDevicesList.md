# apiAssetDevicesList()

GET /assets/{id}/devices - the hardware devices and drivers the inventory agent reported for the asset.

```php
function apiAssetDevicesList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), line 592.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$params` | `array` |  | The ids captured from the URL path, in order, as integers. *([standard](/handbook/standard-parameters.md))* |
| `$body` | `array` |  | The request's JSON body, decoded. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 595 | `apiAssetDevicesList` | read [asset_devices](/code/tables/asset_devices.md) |  |

## Calls

[apiLoadAsset()](/code/functions/apiLoadAsset.md), [apiRespond()](/code/functions/apiRespond.md)

*Nothing calls this function by name.*

