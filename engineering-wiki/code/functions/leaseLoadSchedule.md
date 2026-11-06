# leaseLoadSchedule()

A schedule and its lease, both visible to the analyst.

```php
function leaseLoadSchedule(PDO $conn, int $analystId, int $scheduleId): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 114.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$scheduleId` | `int` |  | the lease_schedules id |

**Returns:** array{0:array,1:array} [schedule, lease]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 116 | `leaseLoadSchedule` | read [lease_schedules](/code/tables/lease_schedules.md) |  |

## Calls

[leaseLoad()](/code/functions/leaseLoad.md)

## Called from

- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseScheduleSave()](/code/functions/leaseScheduleSave.md), [leaseScheduleDelete()](/code/functions/leaseScheduleDelete.md), [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md), [leaseLoadAssetRow()](/code/functions/leaseLoadAssetRow.md)

