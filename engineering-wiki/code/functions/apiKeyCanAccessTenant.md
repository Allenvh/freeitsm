# apiKeyCanAccessTenant()

May this key access this company?

```php
function apiKeyCanAccessTenant(PDO $conn, array $apiKey, int $tenantId): bool
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 226.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiKeyDefaultTenantId()](/code/functions/apiKeyDefaultTenantId.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsList()](/code/functions/apiAssetsList.md), [apiAssetsCreate()](/code/functions/apiAssetsCreate.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesList()](/code/functions/apiChangesList.md), [apiChangesCreate()](/code/functions/apiChangesCreate.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbObjectsList()](/code/functions/apiCmdbObjectsList.md), [apiCmdbObjectsCreate()](/code/functions/apiCmdbObjectsCreate.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractsList()](/code/functions/apiContractsList.md), [apiContractCompany()](/code/functions/apiContractCompany.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentreCompany()](/code/functions/apiCostCentreCompany.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsList()](/code/functions/apiDomainsList.md), [apiDomainsCreate()](/code/functions/apiDomainsCreate.md)
- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiKnowledgeArticlesList()](/code/functions/apiKnowledgeArticlesList.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsList()](/code/functions/apiProblemsList.md), [apiProblemsCreate()](/code/functions/apiProblemsCreate.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsList()](/code/functions/apiProjectsList.md), [apiProjectsCreate()](/code/functions/apiProjectsCreate.md)
- [api/v1/resources/reference.php](/code/files/api/v1/resources/reference.php.md) - in [apiTenantConfigList()](/code/functions/apiTenantConfigList.md)
- [api/v1/resources/tickets.php](/code/files/api/v1/resources/tickets.php.md) - in [apiTicketsList()](/code/functions/apiTicketsList.md), [apiTicketsCreate()](/code/functions/apiTicketsCreate.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersCreate()](/code/functions/apiUsersCreate.md), [apiUsersUpdate()](/code/functions/apiUsersUpdate.md)

