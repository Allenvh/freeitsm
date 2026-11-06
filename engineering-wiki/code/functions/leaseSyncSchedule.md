# leaseSyncSchedule()

Every active asset on a schedule, after its dates change.

```php
function leaseSyncSchedule(PDO $conn, ActorContext $ctx, int $scheduleId): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 708.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$scheduleId` | `int` |  | the lease_schedules id whose dates changed |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 711 | `leaseSyncSchedule` | read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |

## Calls

[leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseSyncAsset()](/code/functions/leaseSyncAsset.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseScheduleSave()](/code/functions/leaseScheduleSave.md)

