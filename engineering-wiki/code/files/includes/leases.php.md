# includes/leases.php

> PHP - 871 lines

Leases (3.5.0) - Assets -> Leases.

A LEASE is the agreement (lessor, agreement number, cost centre, optional
contract, notice period). It has SCHEDULES - a group of kit with its own dates
and cost, because one agreement can cover 10 PCs from one date and 10 monitors
from another. Each schedule has ASSETS, and each asset on a schedule has an
OUTCOME (Assets -> Settings -> Leasing): 'active' kinds (On lease, Extended)
are still out; 'closed' kinds (Returned, Bought out) are finished with.

DATES. An asset on a schedule takes the schedule's dates unless it has its own
(lease_assets.start_date / end_date - "we kept this one laptop six months
longer"). The asset's own lease_start / lease_expiry are KEPT IN STEP with the
effective dates, so everything that already reads them - the asset panel,
Assets -> Table, the dashboard's lease alerts, the calendar - stays right with
no second source of truth:
  - schedule or per-asset dates change  -> `leaseSyncAsset()` writes the asset
    through AssetsService::updateFields() (audited);
  - someone edits the dates on the asset -> AssetsService calls
    `leaseAssetDatesEdited()`, which records them as that asset's own dates.
Only an asset's ACTIVE row is kept in step. Once returned, its dates are left
as the record of what happened.

COMPANY. leases.tenant_id is scoped data like assets: NULL = the Default
company. Its assets, contract and cost centre are all of the same company.
An asset is on at most ONE active lease row at a time (enforced here - the
database cannot express "unique among active rows").

⚠️ Every read goes through `leaseVisibilitySql()` / `leaseListSql()` /
`leaseCanView()`; a read that forgets shows one company's lease to another.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [leaseReady()](/code/functions/leaseReady.md) | 40 | True once Database Verification has created the lease tables. |
| [leaseVisibilitySql()](/code/functions/leaseVisibilitySql.md) | 53 | " AND (...)" - leases in any company this analyst can reach. For links, the asset panel, documents. |
| [leaseListSql()](/code/functions/leaseListSql.md) | 64 | The Leases list and Due back: follow the company switcher, like Assets. |
| [leaseCanView()](/code/functions/leaseCanView.md) | 75 | True when the lease is in a company the analyst may read. |
| [leaseLoad()](/code/functions/leaseLoad.md) | 88 | A lease row the analyst may see, or a not_found ServiceError (never reveals another company's). |
| [leaseTenant()](/code/functions/leaseTenant.md) | 103 | The lease's company as a real id (NULL read as Default). |
| [leaseLoadSchedule()](/code/functions/leaseLoadSchedule.md) | 114 | A schedule and its lease, both visible to the analyst. |
| [leaseActiveSql()](/code/functions/leaseActiveSql.md) | 129 | SQL: this lease_assets row (alias `$la`, outcome alias `$lo`) is still out on lease. |
| [leaseGoneBackSql()](/code/functions/leaseGoneBackSql.md) | 144 | SQL: the asset (alias `$a`) has GONE BACK - it is on a lease, and none of its lease rows is still out (every one is Returned, Bought out, ...). |
| [leaseOutcomes()](/code/functions/leaseOutcomes.md) | 157 | Outcomes, in order. `$activeOnly` drops switched-off ones (still shown where in use). |
| [leaseDefaultOutcomeId()](/code/functions/leaseDefaultOutcomeId.md) | 167 | The outcome a newly added asset starts with: the default, else the first active kind. |
| [leaseDate()](/code/functions/leaseDate.md) | 181 | Validates an optional YYYY-MM-DD date; blank gives null, anything else invalid throws a validation error naming `$label`. |
| [leaseText()](/code/functions/leaseText.md) | 198 | Trims optional text and cuts it to `$max` characters; blank gives null. |
| [leaseSave()](/code/functions/leaseSave.md) | 215 | Create or update a lease. `$in`: id?, name, reference, supplier_id, cost_centre_id, contract_id, notice_days, notes, company_id (create; or a move while it has no assets). |
| [leaseDelete()](/code/functions/leaseDelete.md) | 312 | Delete a lease, its schedules and its asset rows. The assets themselves keep the lease dates they were given - deleting a record of the agreement does not rewrite what the asset says happened. |
| [leaseList()](/code/functions/leaseList.md) | 328 | The Leases list: one row per lease with its schedule dates, asset counts and next end. |
| [leaseGet()](/code/functions/leaseGet.md) | 370 | One lease with its schedules and every asset on them. |
| [leaseScheduleSave()](/code/functions/leaseScheduleSave.md) | 454 | Create or update a schedule. `$in`: id? \| lease_id, name, reference, start_date, end_date, cost, cost_period, currency, order_number, notes. |
| [leaseScheduleDelete()](/code/functions/leaseScheduleDelete.md) | 526 | Delete a schedule and its asset rows (the assets keep their dates). The last schedule cannot go. |
| [leaseAssetsAdd()](/code/functions/leaseAssetsAdd.md) | 551 | Put assets on a schedule. Each must be in the lease's company, visible to the actor, and not already out on another lease. An asset that was on this schedule before and was returned is put back on it. |
| [leaseLoadAssetRow()](/code/functions/leaseLoadAssetRow.md) | 598 | Load a lease_assets row the actor may touch. |
| [leaseAssetUpdate()](/code/functions/leaseAssetUpdate.md) | 616 | Change one asset on a schedule: outcome_id, outcome_date, start_date / end_date (its own dates - blank = the schedule's), notes. Moving it to a CLOSED outcome stamps today as the outcome date if none is given. |
| [leaseAssetRemove()](/code/functions/leaseAssetRemove.md) | 666 | Take an asset off a schedule altogether (a mistake, not a return - use an outcome for that). |
| [leaseActiveRowForAsset()](/code/functions/leaseActiveRowForAsset.md) | 678 | The asset's ACTIVE lease row with its effective dates, or null. |
| [leaseSyncAsset()](/code/functions/leaseSyncAsset.md) | 692 | Write the effective dates onto the asset (audited, through AssetsService). |
| [leaseSyncSchedule()](/code/functions/leaseSyncSchedule.md) | 708 | Every active asset on a schedule, after its dates change. |
| [leaseAssetDatesEdited()](/code/functions/leaseAssetDatesEdited.md) | 722 | Called by AssetsService after an asset's lease_start / lease_expiry change. On an asset that is out on lease, the asset's dates become its OWN dates on the schedule (blank when they match the schedule). Never writes the asset, so it cannot loop with `leaseSyncAsset()`. |
| [leaseHistoryForAsset()](/code/functions/leaseHistoryForAsset.md) | 740 | For the asset panel: every lease this asset has been on, newest first. |
| [leaseDueBack()](/code/functions/leaseDueBack.md) | 768 | Assets still out on lease whose (effective) end falls in a window: `$days` ahead of today, overdue ones always included. `$days` 0 = everything still out. |
| [leaseSyncAllCalendar()](/code/functions/leaseSyncAllCalendar.md) | 821 | Every asset-date calendar entry (warranty, "Lease ends", lease notice) - it calls `leaseSyncCalendar()` itself. |
| [leaseSyncCalendar()](/code/functions/leaseSyncCalendar.md) | 839 | Rebuilds the "Lease notice" calendar entries - the last day to give notice on each schedule that still has kit out. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `leaseReady` | read [lease_assets](/code/tables/lease_assets.md) |  |
| 44 | `leaseReady` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 78 | `leaseCanView` | read [leases](/code/tables/leases.md) |  |
| 91 | `leaseLoad` | read [leases](/code/tables/leases.md) |  |
| 116 | `leaseLoadSchedule` | read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 146 | `leaseGoneBackSql` | read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 160 | `leaseOutcomes` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 169 | `leaseDefaultOutcomeId` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 232 | `leaseSave` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 246 | `leaseSave` | read [suppliers](/code/tables/suppliers.md) |  |
| 290 | `leaseSave` | **update** [leases](/code/tables/leases.md) (`tenant_id`, `name`, `reference`, `supplier_id`, `cost_centre_id`, `contract_id`, `notice_days`, `notes`, `updated_datetime`) |  |
| 296 | `leaseSave` | **insert** [leases](/code/tables/leases.md) (`tenant_id`, `name`, `reference`, `supplier_id`, `cost_centre_id`, `contract_id`, `notice_days`, `notes`, `created_by`, `created_datetime`, `updated_datetime`) |  |
| 301 | `leaseSave` | **insert** [lease_schedules](/code/tables/lease_schedules.md) (`lease_id`, `name`, `display_order`) |  |
| 315 | `leaseDelete` | **delete** [leases](/code/tables/leases.md) |  |
| 339 | `leaseList` | read [lease_schedules](/code/tables/lease_schedules.md), read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md), read [leases](/code/tables/leases.md), read [suppliers](/code/tables/suppliers.md), read [tenants](/code/tables/tenants.md) |  |
| 384 | `leaseGet` | read [suppliers](/code/tables/suppliers.md) |  |
| 396 | `leaseGet` | read [contracts](/code/tables/contracts.md) |  |
| 405 | `leaseGet` | read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 419 | `leaseGet` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [lease_assets](/code/tables/lease_assets.md), read [assets](/code/tables/assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 488 | `leaseScheduleSave` | read [lease_assets](/code/tables/lease_assets.md) |  |
| 498 | `leaseScheduleSave` | **update** [lease_schedules](/code/tables/lease_schedules.md) (`name`, `reference`, `start_date`, `end_date`, `cost`, `cost_period`, `currency`, `order_number`, `notes`) |  |
| 506 | `leaseScheduleSave` | read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 509 | `leaseScheduleSave` | read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 513 | `leaseScheduleSave` | **insert** [lease_schedules](/code/tables/lease_schedules.md) (`lease_id`, `name`, `reference`, `start_date`, `end_date`, `cost`, `cost_period`, `currency`, `order_number`, `notes`, `display_order`) |  |
| 529 | `leaseScheduleDelete` | read [lease_schedules](/code/tables/lease_schedules.md) |  |
| 534 | `leaseScheduleDelete` | **delete** [lease_schedules](/code/tables/lease_schedules.md) |  |
| 563 | `leaseAssetsAdd` | read [assets](/code/tables/assets.md) |  |
| 570 | `leaseAssetsAdd` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 580 | `leaseAssetsAdd` | **insert** [lease_assets](/code/tables/lease_assets.md) (`schedule_id`, `asset_id`, `outcome_id`, `created_by`, `created_datetime`, `updated_datetime`) |  |
| 600 | `leaseLoadAssetRow` | read [lease_assets](/code/tables/lease_assets.md) |  |
| 625 | `leaseAssetUpdate` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 646 | `leaseAssetUpdate` | read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 649 | `leaseAssetUpdate` | read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 654 | `leaseAssetUpdate` | **update** [lease_assets](/code/tables/lease_assets.md) (`outcome_id`, `start_date`, `end_date`, `outcome_date`, `notes`, `updated_datetime`) |  |
| 669 | `leaseAssetRemove` | **delete** [lease_assets](/code/tables/lease_assets.md) |  |
| 682 | `leaseActiveRowForAsset` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 711 | `leaseSyncSchedule` | read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 726 | `leaseAssetDatesEdited` | read [assets](/code/tables/assets.md) |  |
| 733 | `leaseAssetDatesEdited` | **update** [lease_assets](/code/tables/lease_assets.md) (`start_date`, `end_date`, `updated_datetime`) |  |
| 747 | `leaseHistoryForAsset` | read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 778 | `leaseDueBack` | read [users_assets](/code/tables/users_assets.md), read [users](/code/tables/users.md), read [lease_assets](/code/tables/lease_assets.md), read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [assets](/code/tables/assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md), read [suppliers](/code/tables/suppliers.md), read [asset_locations](/code/tables/asset_locations.md), read [tenants](/code/tables/tenants.md) |  |
| 845 | `leaseSyncCalendar` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 849 | `leaseSyncCalendar` | read [lease_schedules](/code/tables/lease_schedules.md), read [leases](/code/tables/leases.md), read [lease_assets](/code/tables/lease_assets.md), read [lease_outcomes](/code/tables/lease_outcomes.md) |  |
| 858 | `leaseSyncCalendar` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |
| 864 | `leaseSyncCalendar` | read [assets](/code/tables/assets.md) |  |

## How it hangs together

**Includes:** [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/cost_centre_assign.php](/code/files/includes/cost_centre_assign.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md)

**Included by:** [api/assets/delete_lease_outcome.php](/code/files/api/assets/delete_lease_outcome.php.md), [api/assets/get_lease_outcomes.php](/code/files/api/assets/get_lease_outcomes.php.md), [api/assets/leases/_boot.php](/code/files/api/assets/leases/_boot.php.md), [api/assets/save_lease_outcome.php](/code/files/api/assets/save_lease_outcome.php.md), [asset-management/leases/view.php](/code/files/asset-management/leases/view.php.md), [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/services/assets.php](/code/files/includes/services/assets.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [analystCanAccessAsset()](/code/functions/analystCanAccessAsset.md), [analystCanAccessTenant()](/code/functions/analystCanAccessTenant.md), [awcColumnExists()](/code/functions/awcColumnExists.md), [awcEnsureCategory()](/code/functions/awcEnsureCategory.md), [awcGetSetting()](/code/functions/awcGetSetting.md), [contractCanView()](/code/functions/contractCanView.md), [contractTenantOf()](/code/functions/contractTenantOf.md), [costCentreCheck()](/code/functions/costCentreCheck.md), [costCentreLabel()](/code/functions/costCentreLabel.md), [costCentreRef()](/code/functions/costCentreRef.md), [documentsDetachParent()](/code/functions/documentsDetachParent.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [getTenantById()](/code/functions/getTenantById.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [syncAssetWarrantyCalendar()](/code/functions/syncAssetWarrantyCalendar.md)

## Traps

> [!WARNING]
> **Line 134:** a returned asset keeps lease_expiry as the record of what happened (see the header), so anything that alerts on lease_expiry must skip these, or a machine handed back in March is "due back" for ever. Call only when `leaseReady()`.

