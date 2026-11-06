# costCentreCheck()

Validate a cost centre being given to a record in company `$tenantId`. Returns the id to store (null = none). Throws ServiceError otherwise. `$currentId` is what the record holds now: keeping an inactive one is fine.

```php
function costCentreCheck(PDO $conn, int $tenantId, $value, ?int $currentId = null): ?int
```

Defined in [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |
| `$value` |  |  | the submitted cost centre id (untrusted); null, '', 0 or '0' mean none |
| `$currentId` | `?int` | `null` | the cost_centres.id the record holds now; null for a new record or none |

**Returns:** `?int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 85 | `costCentreCheck` | read [cost_centres](/code/tables/cost_centres.md) |  |

## Called from

- [api/tickets/save_user.php](/code/files/api/tickets/save_user.php.md)
- [api/v1/resources/users.php](/code/files/api/v1/resources/users.php.md) - in [apiUserCostCentre()](/code/functions/apiUserCostCentre.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md)
- [includes/services/assets.php](/code/files/includes/services/assets.php.md) - in `AssetsService::createAsset`, `AssetsService::updateFields`
- [includes/services/contracts.php](/code/files/includes/services/contracts.php.md) - in `ContractsService::costCentreFor`

