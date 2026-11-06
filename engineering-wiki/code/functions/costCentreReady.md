# costCentreReady()

Has Database Verification added cost_centre_id to `$table` yet?

```php
function costCentreReady(PDO $conn, string $table): bool
```

Defined in [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), line 32.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$table` | `string` |  | the table to probe, e.g. 'assets' (trusted - put straight into SQL) |

**Returns:** `bool`

## Called from

- [api/assets/get_assets.php](/code/files/api/assets/get_assets.php.md)
- [api/assets/leases/options.php](/code/files/api/assets/leases/options.php.md)
- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [api/v1/resources/assets.php](/code/files/api/v1/resources/assets.php.md) - in [apiAssetSelect()](/code/functions/apiAssetSelect.md)
- [api/v1/resources/contracts.php](/code/files/api/v1/resources/contracts.php.md) - in [apiContractSelect()](/code/functions/apiContractSelect.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUserColumns()](/code/functions/apiUserColumns.md), [apiUserCostCentre()](/code/functions/apiUserCostCentre.md), [apiUsersUpdate()](/code/functions/apiUsersUpdate.md)
- [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md) - in [costCentreSelectSql()](/code/functions/costCentreSelectSql.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::createAsset`, `AssetsService::updateFields`
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::costCentreFor`, `ContractsService::writeCostCentre`

