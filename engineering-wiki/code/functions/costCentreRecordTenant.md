# costCentreRecordTenant()

A record's company for cost centre purposes: its tenant_id, or Default when NULL.

```php
function costCentreRecordTenant(PDO $conn, $tenantId): int
```

Defined in [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), line 45.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` |  |  | the record's raw tenant_id column value; null, '' or 0 mean the Default company |

**Returns:** `int`

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md)

## Called from

- [api/cost-centres/options.php](/code/files/api/cost-centres/options.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUserCostCentre()](/code/functions/apiUserCostCentre.md), [apiUsersUpdate()](/code/functions/apiUsersUpdate.md)
- [includes/services/asset_import.php](/code/files/includes/services/asset_import.php.md) - in `AssetImportService::resolveLookups`
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::createAsset`, `AssetsService::updateFields`

