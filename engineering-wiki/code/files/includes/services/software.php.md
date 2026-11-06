# includes/services/software.php

> PHP - 221 lines

SoftwareService — the single home for the software module's write rules.

Only the LICENCE writes are duplicated between the UI (api/software/
save_licence.php, delete_licence.php) and the REST API (api/v1/resources/
software.php); the inventory is agent-owned/read-only and the dashboard-widget
+ legacy-apikey endpoints are UI-only with no API twin, so they keep their own
code (nothing to de-duplicate there). This service therefore covers licences.

Each caller passes an ActorContext + canonical input; this layer validates,
writes, and returns the affected id (plus a created flag for the save adapter)
or throws ServiceError. It never emits HTTP.

Canonical behaviour = the API resource's (see docs/design/service-layer.md):
the target app must exist (422, not a raw FK 500), quantity/cost/dates are
validated (422, not silently coerced), empty optional strings normalise to
null, and missing currency/status fall back to GBP/Active. created_by is
stamped on insert and never touched on update. created_at/updated_at keep the
table defaults on insert; updated_at is stamped UTC on update.

Input keys are canonical snake_case (app_id, licence_type, licence_key,
quantity, renewal_date, notice_period_days, portal_url, cost, currency,
purchase_date, vendor_contact, notes, status). The UI already sends these.

## Classes

- [SoftwareService](/code/classes/SoftwareService.md) (class, line 31)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `SoftwareService::saveLicence` | **update** [software_licences](/code/tables/software_licences.md) (`app_id`, `licence_type`, `licence_key`, `quantity`, `renewal_date`, `notice_period_days`, `portal_url`, `cost`, `currency`, `purchase_date`, `vendor_contact`, `notes`) |  |
| 67 | `SoftwareService::saveLicence` | **insert** [software_licences](/code/tables/software_licences.md) (`app_id`, `licence_type`, `licence_key`, `quantity`, `renewal_date`, `notice_period_days`, `portal_url`, `cost`, `currency`, `purchase_date`, `vendor_contact`, `notes`) |  |
| 91 | `SoftwareService::deleteLicence` | **delete** [software_licences](/code/tables/software_licences.md) |  |
| 132 | `SoftwareService::loadLicenceRow` | read [software_licences](/code/tables/software_licences.md) |  |
| 160 | `SoftwareService::collectFields` | read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/software_licence_calendar.php](/code/files/includes/software_licence_calendar.php.md)

**Included by:** [api/software/delete_licence.php](/code/files/api/software/delete_licence.php.md), [api/software/save_licence.php](/code/files/api/software/save_licence.php.md), [api/v1/resources/software.php](/code/files/api/v1/resources/software.php.md)

**Calls:** [syncSoftwareLicenceCalendar()](/code/functions/syncSoftwareLicenceCalendar.md)

