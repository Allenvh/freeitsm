# includes/asset_warranty_calendar.php

> PHP - 326 lines

Asset expiry dates → Calendar sync.

Keeps two sets of auto-generated, all-day calendar events in step with the
dates on an asset:

  warranty_expiry → "Warranty expiry: HOST", source 'asset_warranty'
  lease_expiry    → "Lease ends: HOST",      source 'asset_lease'

Each is gated by its own setting (asset_warranty_surface / asset_lease_surface)
and cleared and rebuilt on its own `source`, so a full resync never disturbs
user-created events, and switching one kind off never removes the other.

The file is still named after warranty because warranty is what it did first
and the name is referenced from several call sites; what it covers is the
list above, not the filename.

Called opportunistically when an asset's warranty or lease date changes
(update_asset_field.php) and on demand when either setting is saved
(sync_warranty_calendar.php). Cheap full resync — these edits are rare and the
event set is small.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md) | 37 | Rebuilds the auto-generated warranty-expiry and lease-end calendar entries from the asset dates, then the lease "give notice" entries. |
| [awcSyncKind()](/code/functions/awcSyncKind.md) | 116 | Clear and rebuild one kind of auto-generated asset date entry. |
| [awcEnsureCategory()](/code/functions/awcEnsureCategory.md) | 203 | Which calendar category should this kind of entry use? |
| [awcAdoptExistingCategory()](/code/functions/awcAdoptExistingCategory.md) | 251 | Adopt whatever category this kind of entry is already filed under. |
| [awcSetSetting()](/code/functions/awcSetSetting.md) | 278 | Remember a value; best-effort, because losing it only costs one lookup. |
| [awcGetSetting()](/code/functions/awcGetSetting.md) | 294 | Reads one system_settings value, returning `$default` when it is missing, empty or unreadable. |
| [awcColumnExists()](/code/functions/awcColumnExists.md) | 312 | True when the table has the column (information_schema), so the sync can wait for Database Verification instead of failing. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 132 | `awcSyncKind` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 151 | `awcSyncKind` | read [assets](/code/tables/assets.md) |  |
| 161 | `awcSyncKind` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |
| 210 | `awcEnsureCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 220 | `awcEnsureCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 230 | `awcEnsureCategory` | **insert** [calendar_categories](/code/tables/calendar_categories.md) (`name`, `color`, `is_active`) |  |
| 256 | `awcAdoptExistingCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 262 | `awcAdoptExistingCategory` | read [calendar_events](/code/tables/calendar_events.md) |  |
| 282 | `awcSetSetting` | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |
| 297 | `awcGetSetting` | read [system_settings](/code/tables/system_settings.md) |  |

## How it hangs together

**Includes:** [includes/leases.php](/code/files/includes/leases.php.md)

**Included by:** [api/assets/sync_warranty_calendar.php](/code/files/api/assets/sync_warranty_calendar.php.md), [includes/domains/calendar.php](/code/files/includes/domains/calendar.php.md), [includes/leases.php](/code/files/includes/leases.php.md), [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md)

**Calls:** [leaseGoneBackSql()](/code/functions/leaseGoneBackSql.md), [leaseReady()](/code/functions/leaseReady.md), [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md)

