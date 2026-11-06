# apiSerializeAsset()

Shapes an asset row for the API - identity, company, type, status, location with its path, hardware, operating system and the rest of the record.

```php
function apiSerializeAsset(PDO $conn, array $r): array
```

Defined in [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md), line 105.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$r` | `array` |  | the asset row as `apiAssetSelect()` loads it, with its type, status, location, supplier and company names |

**Returns:** `array`

## Calls

[apiAssetCustomFields()](/code/functions/apiAssetCustomFields.md), [apiAssetLocationPath()](/code/functions/apiAssetLocationPath.md), [apiIsoDate()](/code/functions/apiIsoDate.md)

## Called from

- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsList()](/code/functions/apiAssetsList.md), [apiAssetsGet()](/code/functions/apiAssetsGet.md), [apiAssetsCreate()](/code/functions/apiAssetsCreate.md), [apiAssetsUpdate()](/code/functions/apiAssetsUpdate.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::fullObjectLoaders`

