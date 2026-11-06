# leaseGet()

One lease with its schedules and every asset on them.

```php
function leaseGet(PDO $conn, int $analystId, int $id): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 370.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the leases id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 384 | `leaseGet` | read [suppliers](/code/tables/suppliers.md) |  |
| 396 | `leaseGet` | read [contracts](/code/tables/contracts.md) |  |
| 405 | `leaseGet` | read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 419 | `leaseGet` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [lease_assets](/code/tables/lease_assets.md), read [assets](/code/tables/assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## Calls

[contractCanView()](/code/functions/contractCanView.md), [costCentreLabel()](/code/functions/costCentreLabel.md), [costCentreRef()](/code/functions/costCentreRef.md), [getTenantById()](/code/functions/getTenantById.md), [leaseLoad()](/code/functions/leaseLoad.md), [leaseTenant()](/code/functions/leaseTenant.md)

## Called from

- [api/assets/leases/get.php](/code/files/api/assets/leases/get.php.md)

