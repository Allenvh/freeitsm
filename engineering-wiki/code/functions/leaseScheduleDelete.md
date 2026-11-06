# leaseScheduleDelete()

Delete a schedule and its asset rows (the assets keep their dates). The last schedule cannot go.

```php
function leaseScheduleDelete(PDO $conn, ActorContext $ctx, int $id): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 526.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the lease_schedules id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 529 | `leaseScheduleDelete` | read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 534 | `leaseScheduleDelete` | **delete** [lease_schedules](/code/tables/lease_schedules.md) |  |

## Calls

[leaseLoadSchedule()](/code/functions/leaseLoadSchedule.md), [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md)

## Called from

- [api/assets/leases/schedule_delete.php](/code/files/api/assets/leases/schedule_delete.php.md)

