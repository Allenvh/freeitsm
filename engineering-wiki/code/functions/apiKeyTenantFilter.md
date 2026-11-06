# apiKeyTenantFilter()

SQL predicate scoping any tenant_id-carrying list query to the key's companies. Mirrors `ticketTenantFilter()`: NULL tenant_id rows belong to the Default company. Works for tickets ('t'), problems ('p'), and any future tenant-scoped module.

```php
function apiKeyTenantFilter(PDO $conn, array $apiKey, string $alias): array
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 285.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$alias` | `string` |  | the alias the query gives the table; empty for the bare tenant_id column |

**Returns:** array [sqlFragment, params]

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiKeyTicketFilter()](/code/functions/apiKeyTicketFilter.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetsList()](/code/functions/apiAssetsList.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiChangesList()](/code/functions/apiChangesList.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbClassesList()](/code/functions/apiCmdbClassesList.md), [apiCmdbObjectsList()](/code/functions/apiCmdbObjectsList.md), [apiCmdbObjectsGet()](/code/functions/apiCmdbObjectsGet.md), [apiCmdbObjectTicketsList()](/code/functions/apiCmdbObjectTicketsList.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiCostCentresList()](/code/functions/apiCostCentresList.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiDomainsList()](/code/functions/apiDomainsList.md), [apiDomainAccountsList()](/code/functions/apiDomainAccountsList.md)
- [api/v1/resources/problems.php](/code/files/api/v1/resources/problems.php.md) - in [apiProblemsList()](/code/functions/apiProblemsList.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiProjectsList()](/code/functions/apiProjectsList.md)
- [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md) - in [apiSoftwareAppMachinesList()](/code/functions/apiSoftwareAppMachinesList.md)
- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiTasksList()](/code/functions/apiTasksList.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersList()](/code/functions/apiUsersList.md)

