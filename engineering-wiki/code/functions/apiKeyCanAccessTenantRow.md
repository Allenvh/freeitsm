# apiKeyCanAccessTenantRow()

May this key access this row of a tenant-scoped table (by its owning company)? Unknown id => false. The generic by-id gate behind apiKeyCanAccessTicket / apiKeyCanAccessProblem. `$table` is a developer literal, never user input.

```php
function apiKeyCanAccessTenantRow(PDO $conn, array $apiKey, string $table, int $rowId): bool
```

Defined in [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md), line 245.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$apiKey` | `array` |  | The API key that authenticated the call - its scopes and company limit. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the table to look in - a literal from the code, never user input |
| `$rowId` | `int` |  | the row's id |

**Returns:** `bool`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

## Called from

- [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) - in [apiKeyCanAccessTicket()](/code/functions/apiKeyCanAccessTicket.md), [apiKeyCanAccessProblem()](/code/functions/apiKeyCanAccessProblem.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiLoadAsset()](/code/functions/apiLoadAsset.md)
- [api/v1/resources/changes.php](/code/files/api/v1/resources/changes.php.md) - in [apiLoadChange()](/code/functions/apiLoadChange.md)
- [api/v1/resources/cmdb.php](/code/files/api/v1/resources/cmdb.php.md) - in [apiCmdbLoadObject()](/code/functions/apiCmdbLoadObject.md)
- [api/v1/resources/cost_centres.php](/code/files/api/v1/resources/cost_centres.php.md) - in [apiLoadCostCentre()](/code/functions/apiLoadCostCentre.md)
- [api/v1/resources/domains.php](/code/files/api/v1/resources/domains.php.md) - in [apiLoadDomain()](/code/functions/apiLoadDomain.md)
- [api/v1/resources/network_mapper.php](/code/files/api/v1/resources/network_mapper.php.md) - in [apiNmValidateObjectExists()](/code/functions/apiNmValidateObjectExists.md)
- [api/v1/resources/projects.php](/code/files/api/v1/resources/projects.php.md) - in [apiLoadProject()](/code/functions/apiLoadProject.md)
- [api/v1/resources/tasks.php](/code/files/api/v1/resources/tasks.php.md) - in [apiLoadTask()](/code/functions/apiLoadTask.md), [apiTasksGet()](/code/functions/apiTasksGet.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUsersGet()](/code/functions/apiUsersGet.md), [apiUsersUpdate()](/code/functions/apiUsersUpdate.md)

