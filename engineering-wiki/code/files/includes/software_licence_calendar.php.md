# includes/software_licence_calendar.php

> PHP - 151 lines

Software licence renewals → Calendar sync (#1551).

A deliberate copy of the shape in includes/asset_warranty_calendar.php rather
than a new mechanism: `calendar_events.source` already exists precisely so a
generator can wipe and reinsert its own events without touching anything a
person typed, and assets have been doing this for a while. Software was simply
never wired into it.

---------------------------------------------------------------------------
WHY THIS EXISTS AT ALL
---------------------------------------------------------------------------
`software_licences` has carried `renewal_date` and `notice_period_days` since
it shipped, and both already drove a "due soon" colour — but only for somebody
who happened to open the Licences page that week. A contract ending the same
day appeared on Watchtower AND in the calendar; a £12k software renewal
appeared nowhere. This closes half of that (the Watchtower card closes the
other half).

---------------------------------------------------------------------------
TWO EVENTS PER LICENCE, NOT ONE
---------------------------------------------------------------------------
The renewal date is when the money goes out. The NOTICE date — renewal minus
notice_period_days — is the last day you can still walk away, and it is the
one that actually costs you if you miss it. A calendar showing only the
renewal tells you about the deadline on the day it is already too late.

Unlike contracts, which store their own `notice_date` column, this one is
derived. A licence with no notice period gets no notice event: there is no
deadline to miss, and inventing a default 30 days would put a fictional
commitment in somebody's calendar.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [syncSoftwareLicenceCalendar()](/code/functions/syncSoftwareLicenceCalendar.md) | 47 | Rebuilds the software renewal entries in the calendar from the active licences' renewal dates. |
| [slcEnsureRenewalCategory()](/code/functions/slcEnsureRenewalCategory.md) | 134 | Find or create the "Renewals" calendar category; returns its id (or null). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `syncSoftwareLicenceCalendar` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 72 | `syncSoftwareLicenceCalendar` | read [software_licences](/code/tables/software_licences.md), read [software_inventory_apps](/code/tables/software_inventory_apps.md) |  |
| 84 | `syncSoftwareLicenceCalendar` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |
| 136 | `slcEnsureRenewalCategory` | read [calendar_categories](/code/tables/calendar_categories.md) |  |
| 143 | `slcEnsureRenewalCategory` | **insert** [calendar_categories](/code/tables/calendar_categories.md) (`name`, `color`, `is_active`) |  |

## How it hangs together

**Includes:** [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md)

**Included by:** [api/software/sync_renewal_calendar.php](/code/files/api/software/sync_renewal_calendar.php.md), [includes/services/software.php](/code/files/includes/services/software.php.md)

**Calls:** [awcColumnExists()](/code/functions/awcColumnExists.md), [awcGetSetting()](/code/functions/awcGetSetting.md)

