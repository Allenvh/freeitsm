# includes/domains/calendar.php

> PHP - 105 lines

Domains — expiry dates as all-day Calendar entries. Two kinds:

domain_expiry  the registration       "Domain renewals"       domain_expiry_surface
  domain_cert    the live certificate   "Certificate renewals"  domain_cert_surface

The same machinery as asset warranties and leases, reused rather than copied
(see Warranty-and-Lease-Alerts-Developer-Guide on the wiki):

 - 🔑 EACH KIND HAS ITS OWN `source` MARKER AND ITS OWN SETTING. Sync clears
   and rebuilds one marker at a time, so switching certificates off removes
   only certificate entries; registrations, warranties and anything typed by
   hand are untouched.

 - 🔑 THE CATEGORY KNOWS WHAT IT IS. awcAdoptExistingCategory /
   awcEnsureCategory remember it by id (`<source>_category_id`), so an
   operator can rename "Certificate renewals" to their own language or
   wording - the only translation a stored row can have - and the next sync
   files into the renamed one instead of making a second. Adoption runs
   BEFORE the delete, because the entries about to be cleared are the only
   record of which category they were in.

 - Clearing happens even when the kind is switched off: that is what makes
   switching it off actually remove the entries.

Only domains whose status still wants alerts are drawn: a domain deliberately
"Letting lapse" should not keep shouting from the calendar.

⚠️ Calendar entries have no company. On a multi-company install every
analyst who can open the Calendar sees these, as they already see contract
and warranty dates — the Domains help page says so.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [domainSyncExpiryCalendar()](/code/functions/domainSyncExpiryCalendar.md) | 39 | Both kinds. The name is kept: every caller already asks for "the calendar". |
| [domainSyncCalendarKind()](/code/functions/domainSyncCalendarKind.md) | 78 | One kind: adopt its category, clear its entries, and - if its surface says calendar - draw one all-day entry per domain with a date in `$column`. The column name is interpolated, never bound; it comes only from this file. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 | `domainSyncCalendarKind` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 87 | `domainSyncCalendarKind` | read [domains](/code/tables/domains.md), read [domain_statuses](/code/tables/domain_statuses.md) |  |
| 94 | `domainSyncCalendarKind` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |

## How it hangs together

**Includes:** [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), [includes/domains/settings.php](/code/files/includes/domains/settings.php.md)

**Included by:** [api/domains/statuses.php](/code/files/api/domains/statuses.php.md), [includes/domains/scheduler.php](/code/files/includes/domains/scheduler.php.md), [includes/services/domains.php](/code/files/includes/services/domains.php.md)

**Calls:** [awcAdoptExistingCategory()](/code/functions/awcAdoptExistingCategory.md), [awcColumnExists()](/code/functions/awcColumnExists.md), [awcEnsureCategory()](/code/functions/awcEnsureCategory.md), [domainSetting()](/code/functions/domainSetting.md)

