# leaseAssetsAdd()

Put assets on a schedule. Each must be in the lease's company, visible to the actor, and not already out on another lease. An asset that was on this schedule before and was returned is put back on it.

```php
function leaseAssetsAdd(PDO $conn, ActorContext $ctx, int $scheduleId, array $assetIds): array
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 551.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$scheduleId` | `int` |  | the lease_schedules id to put the assets on |
| `$assetIds` | `array` |  | the asset ids to add (cast to int, duplicates and 0 ignored) |

**Returns:** array{added:int, refused:array<int,string>} refused: asset id => reason

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 563 | `leaseAssetsAdd` | read [assets](/code/tables/assets.md) |  |
| 570 | `leaseAssetsAdd` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 580 | `leaseAssetsAdd` | **insert** [lease_assets](/code/tables/lease_assets.md) (`schedule_id`, `asset_id`, `outcome_id`, `created_by`, `created_datetime`, `updated_datetime`) |  |

## Calls

[analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseDefaultOutcomeId()](/code/functions/leaseDefaultOutcomeId.md), [leaseLoadSchedule()](/code/functions/leaseLoadSchedule.md), [leaseSyncAsset()](/code/functions/leaseSyncAsset.md), [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md), [leaseTenant()](/code/functions/leaseTenant.md)

## Called from

- [api/assets/leases/assets_add.php](/code/files/api/assets/leases/assets_add.php.md)

