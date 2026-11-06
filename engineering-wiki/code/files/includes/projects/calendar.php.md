# includes/projects/calendar.php

> PHP - 124 lines

Projects - the dates that matter, as all-day Calendar entries (3.2.0). Three kinds:

project_end        a project's target end date    "Project due: NAME (PRJ-0042)"
  project_stage      each open stage's end date     "Stage ends: STAGE - NAME"
  project_milestone  each milestone not yet reached "Milestone: MILESTONE - NAME" (3.3.0)

The same machinery as warranties, software renewals and Domains, reused rather
than copied (Warranty-and-Lease-Alerts-Developer-Guide on the wiki):

 - 🔑 EACH KIND HAS ITS OWN `source` MARKER. Sync clears and rebuilds one
   marker at a time, so nothing a person typed into the Calendar is touched,
   and switching stage ends off removes only stage entries.

 - Both kinds file into ONE category, "Projects", found by name the first
   time and remembered by id after that (`<source>_category_id`), so an
   operator can rename it into their own language and the next sync keeps
   using it. Adoption runs BEFORE the delete, because the entries about to be
   cleared are the only record of which category they were in.

 - A full resync after every change that can move a date. Projects are few
   and stages fewer; a per-project update would need a reference column that
   calendar_events does not have.

Only LIVE plans are drawn: a proposed or active project. A closed or
cancelled one's dates are history, and an on-hold one's are known to be
wrong until it restarts. A stage is drawn until it closes.

⚠️ Calendar entries have no company. On a multi-company install every
analyst who can open the Calendar sees every project's dates, as they already
see contract and warranty dates - the Projects help page says so.

⚠️ The titles are stored rows, so they are English, as the warranty and
Domains entries are. The category name is the part an operator can change.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectSyncCalendar()](/code/functions/projectSyncCalendar.md) | 46 | Redraw both kinds. Never throws: a calendar that is out of step must never stop a project being saved. |
| [projectSyncCalendarKind()](/code/functions/projectSyncCalendarKind.md) | 105 | One kind: adopt its category, clear its entries, and - when on - draw one all-day entry per row. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |
| 64 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |
| 68 | `projectSyncCalendar` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 73 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |
| 81 | `projectSyncCalendar` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |
| 86 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |
| 108 | `projectSyncCalendarKind` | **delete** [calendar_events](/code/tables/calendar_events.md) |  |
| 113 | `projectSyncCalendarKind` | **insert** [calendar_events](/code/tables/calendar_events.md) (`title`, `description`, `category_id`, `start_datetime`, `end_datetime`, `all_day`, `created_by`, `source`) |  |

## How it hangs together

**Includes:** [includes/asset_warranty_calendar.php](/code/files/includes/asset_warranty_calendar.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/projects/milestones.php](/code/files/includes/projects/milestones.php.md)

**Included by:** [api/projects/settings.php](/code/files/api/projects/settings.php.md), [includes/services/projects.php](/code/files/includes/services/projects.php.md)

**Calls:** [awcAdoptExistingCategory()](/code/functions/awcAdoptExistingCategory.md), [awcColumnExists()](/code/functions/awcColumnExists.md), [awcEnsureCategory()](/code/functions/awcEnsureCategory.md), [projectCode()](/code/functions/projectCode.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectSettings()](/code/functions/projectSettings.md), [projectVisibilityReady()](/code/functions/projectVisibilityReady.md)

