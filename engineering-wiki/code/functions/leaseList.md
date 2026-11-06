# leaseList()

The Leases list: one row per lease with its schedule dates, asset counts and next end.

```php
function leaseList(PDO $conn, int $analystId, string $search = ''): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 328.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$search` | `string` | `''` | text matched (LIKE) against the lease name, reference and supplier names; '' for all |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 339 | `leaseList` | read [lease_schedules](/code/tables/lease_schedules.md), read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md), read [leases](/code/tables/leases.md), read [suppliers](/code/tables/suppliers.md), read [tenants](/code/tables/tenants.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseListSql()](/code/functions/leaseListSql.md)

## Called from

- [api/assets/leases/list.php](/code/files/api/assets/leases/list.php.md)

