# apiAssetsList()

GET /assets

```php
function apiAssetsList(PDO $conn, array $apiKey, array $params, array $body): void
```

Defined in [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), line 244.

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
| 274 | `apiAssetsList` | read [users_assets](/code/tables/users_assets.md) |  |
| 278 | `apiAssetsList` | read [users_assets](/code/tables/users_assets.md) |  |
| 323 | `apiAssetsList` | read [assets](/code/tables/assets.md) |  |

## Calls

[apiAssetSelect()](/code/functions/apiAssetSelect.md), [apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md), [apiSerializeAsset()](/code/functions/apiSerializeAsset.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [naive_today_sql()](/code/functions/naive_today_sql.md)

*Nothing calls this function by name.*

