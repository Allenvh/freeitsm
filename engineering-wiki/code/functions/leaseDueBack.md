# leaseDueBack()

Assets still out on lease whose (effective) end falls in a window: `$days` ahead of today, overdue ones always included. `$days` 0 = everything still out.

```php
function leaseDueBack(PDO $conn, int $analystId, int $days = 90, int $leaseId = 0): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 768.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$days` | `int` | `90` | how many days ahead of today to look; overdue rows are always included, 0 = everything still out |
| `$leaseId` | `int` | `0` | limit to one lease; 0 for every lease |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 778 | `leaseDueBack` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [assets](/code/tables/assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md), read [suppliers](/code/tables/suppliers.md), read [asset_locations](/code/tables/asset_locations.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseListSql()](/code/functions/leaseListSql.md)

## Called from

- [api/assets/leases/due_back.php](/code/files/api/assets/leases/due_back.php.md)

