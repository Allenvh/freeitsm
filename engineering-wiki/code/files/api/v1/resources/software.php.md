# api/v1/resources/software.php

> API endpoint - 392 lines

FreeITSM REST API v1 — software resource (inventory + licences).

Inventory (apps + per-host installs) is 100% agent-owned in the product —
analysts can't edit any of it — so the API exposes it READ-ONLY, mirroring
get_apps.php's install-count query and system-component filters. Licences
are the writable half, mirroring save_licence.php (app_id + licence_type
mandatory, currency defaults GBP, status defaults Active, created_by
stamped on insert and never touched on update).

Two deliberate API additions the UI doesn't have, documented:
  - COMPLIANCE numbers: licence rows and app detail carry install counts
    (distinct non-system-component hosts) next to licensed seats, so
    seats-vs-installs reports are one call — the UI never computes this.
  - SERVER-SIDE renewal filters + a computed renewal_status
    (ok / due_soon / overdue) matching the licence screen's client-side
    colour logic (due_soon = within notice_period_days, default 30).

The legacy plaintext apikeys management that lives in this module's
settings is NOT part of this API (v1 has its own key system).
Software tables are install-wide (no tenant_id — matches the UI).

Licence WRITES are delegated to SoftwareService (includes/services/software.php)
so the UI and this API share one code path; the read handlers + serializers
below stay here and are called to re-load the affected row after a write.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/software.php` |
| Protected by | REST API key |
| Reads | `GET q`, `GET filter`, `GET sort`, `GET app_id`, `GET status`, `GET renewal_within_days`, `GET renewal_overdue`, `GET due_soon` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiSoftwareAppsList()](/code/functions/apiSoftwareAppsList.md) | 45 | GET /software/apps - the discovered applications with install and licence counts, paginated. |
| [apiSoftwareLoadApp()](/code/functions/apiSoftwareLoadApp.md) | 123 | Loads one discovered application row, or answers 404 and stops. |
| [apiSoftwareInstallCount()](/code/functions/apiSoftwareInstallCount.md) | 138 | Distinct non-system-component hosts for an app — the compliance install count. |
| [apiSoftwareAppsGet()](/code/functions/apiSoftwareAppsGet.md) | 148 | GET /software/apps/{id} — app + licences + compliance numbers |
| [apiSoftwareAppMachinesList()](/code/functions/apiSoftwareAppMachinesList.md) | 195 | GET /software/apps/{id}/machines — mirrors get_app_machines.php (+ asset_id) |
| [apiSoftwareLicenceSelect()](/code/functions/apiSoftwareLicenceSelect.md) | 228 | The SELECT for software licences, with the application's name and publisher, the creator's name and how many machines have the application installed. |
| [apiSerializeLicence()](/code/functions/apiSerializeLicence.md) | 245 | Shapes a licence row for the API, adding renewal_status - overdue, due_soon (inside the notice period, 30 days by default) or ok - computed the way the licence screen colours it. |
| [apiLoadLicence()](/code/functions/apiLoadLicence.md) | 289 | Loads one licence row (apiSoftwareLicenceSelect), or answers 404 and stops. |
| [apiSoftwareLicencesList()](/code/functions/apiSoftwareLicencesList.md) | 300 | GET /software/licences |
| [apiSoftwareLicencesGet()](/code/functions/apiSoftwareLicencesGet.md) | 365 | GET /software/licences/{id} - one licence. |
| [apiSoftwareLicencesCreate()](/code/functions/apiSoftwareLicencesCreate.md) | 370 | POST /software/licences |
| [apiSoftwareLicencesUpdate()](/code/functions/apiSoftwareLicencesUpdate.md) | 378 | PATCH /software/licences/{id} |
| [apiSoftwareLicencesDelete()](/code/functions/apiSoftwareLicencesDelete.md) | 386 | DELETE /software/licences/{id} — leaf table, mirrors delete_licence.php |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 78 | `apiSoftwareAppsList` | read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 88 | `apiSoftwareAppsList` | read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 124 | `apiSoftwareLoadApp` | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 140 | `apiSoftwareInstallCount` | read [software_inventory_detail](/code/tables/software_inventory_detail.md) |  |
| 153 | `apiSoftwareAppsGet` | read [software_licences](/code/tables/software_licences.md) |  |
| 202 | `apiSoftwareAppMachinesList` | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [assets](/code/tables/assets.md) |  |
| 229 | `apiSoftwareLicenceSelect` | read [software_inventory_detail](/code/tables/software_inventory_detail.md), read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md), read [analysts](/code/tables/analysts.md) |  |
| 349 | `apiSoftwareLicencesList` | read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/software.php](/code/files/includes/services/software.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyTenantFilter()](/code/functions/apiKeyTenantFilter.md), [apiPagination()](/code/functions/apiPagination.md), [apiRespond()](/code/functions/apiRespond.md), [naive_today_sql()](/code/functions/naive_today_sql.md)

