# apiKeyDefaultTenantId()

The company a NEW ticket created by this key belongs to when the request doesn't say: the Default company if the key may access it, otherwise the key's first scoped company.

```php
function apiKeyDefaultTenantId(PDO $conn, array $apiKey): int
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 365.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Calls

[apiError()](/code/functions/apiError.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsCreate()](/code/functions/apiAssetsCreate.md), [apiAssetTypesList()](/code/functions/apiAssetTypesList.md), [apiAssetStatusesList()](/code/functions/apiAssetStatusesList.md), [apiAssetLocationsList()](/code/functions/apiAssetLocationsList.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesCreate()](/code/functions/apiChangesCreate.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectsCreate()](/code/functions/apiCmdbObjectsCreate.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractCompany()](/code/functions/apiContractCompany.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentreCompany()](/code/functions/apiCostCentreCompany.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsCreate()](/code/functions/apiDomainsCreate.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsCreate()](/code/functions/apiProblemsCreate.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsCreate()](/code/functions/apiProjectsCreate.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsCreate()](/code/functions/apiTicketsCreate.md)

