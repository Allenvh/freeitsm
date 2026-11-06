# leaseSyncCalendar()

Rebuilds the "Lease notice" calendar entries - the last day to give notice on each schedule that still has kit out.

Deletes every entry from source 'asset_lease_notice' and, when the lease alert surface
includes the calendar, adds one all-day event per schedule. Never throws, so a calendar
problem cannot fail a lease save.

```php
function leaseSyncCalendar(PDO $conn): void
```

Defined in [includes/leases.php](/code/files/includes/leases.php.md), line 839.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

**Writes:** calendar_events - one all-day entry per schedule, on end date minus notice days

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 845 | `leaseSyncCalendar` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 849 | `leaseSyncCalendar` | read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 858 | `leaseSyncCalendar` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |
| 864 | `leaseSyncCalendar` | read [assets](/code/tables/assets.md) |  |

## Calls

[awcColumnExists()](/code/functions/awcColumnExists.md), [awcEnsureCategory()](/code/functions/awcEnsureCategory.md), [awcGetSetting()](/code/functions/awcGetSetting.md), [leaseActiveSql()](/code/functions/leaseActiveSql.md), [leaseReady()](/code/functions/leaseReady.md)

## Called from

- [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md)
- [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md) - in [syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseSave()](/code/functions/leaseSave.md), [leaseDelete()](/code/functions/leaseDelete.md), [leaseScheduleSave()](/code/functions/leaseScheduleSave.md), [leaseScheduleDelete()](/code/functions/leaseScheduleDelete.md), [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md), [leaseAssetDatesEdited()](/code/functions/leaseAssetDatesEdited.md)

