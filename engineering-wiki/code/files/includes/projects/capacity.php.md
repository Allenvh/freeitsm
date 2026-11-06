# includes/projects/capacity.php

> PHP - 264 lines

Projects - capacity (3.3.0): who is over-committed in the weeks ahead, once project work and service-desk duty are put side by side.

Per analyst, per week (Monday to Sunday, from this week):

  project hours  every OPEN project task they are assigned, in a live project
                 the viewer can see: its estimate less the time already logged
                 on it (and its subtasks), spread evenly over the WORKING days
                 (Monday to Friday unless project_capacity_days says
                 otherwise) from its start - or today, if that is later
                 - to its due date. Late work (due before today) lands in this
                 week. Only the part inside a week is counted in that week.
  desk hours     their shifts on the service-desk rota that week (Tickets ->
                 Rota), each shift's length. On the rota, they are not on the
                 project. Shown only to a viewer who can open Tickets, and
                 not at all when project_capacity_desk is off (an on-call
                 rota is not time away from projects).
  load           (project + desk) / hours per week (Projects -> Settings ->
                 Health). Amber at the amber share, red over 100%.

Beside the weeks: their open tickets (context, not hours - a ticket has no
size), how many live projects they have open work on (flagged at the setting),
work with no due date ("unscheduled", hours), and tasks with no estimate (a
count - unknown work is a risk, not zero).

🔑 WORKED OUT, NEVER STORED, like health. Nothing here writes.

🔑 WHOSE: projects through activeTenantReadFilter (the viewer's company, as on
the portfolio); tickets through allAccessibleTenantsFilter (every company the
viewer can see - a person's load is all of it that the viewer may know about).
The rota is install-wide, as on the rota screen.

Who appears: analysts with open assigned work in those projects, and analyst
members of them (so somebody on a project with nothing assigned shows as free).
Work assigned to a TEAM and nobody in it is totalled separately - it is real
work that nobody has picked up.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [projectCapacityWorkdays()](/code/functions/projectCapacityWorkdays.md) | 53 | The working days from `$from` to `$to` inclusive, as Y-m-d. `$days`: ISO weekdays (1 = Monday), Monday to Friday by default. |
| [projectCapacityShiftHours()](/code/functions/projectCapacityShiftHours.md) | 68 | Length of a rota shift in hours; a shift that ends at or before it starts runs past midnight. |
| [projectCapacity()](/code/functions/projectCapacity.md) | 83 | The capacity view: each analyst's project hours and rota desk hours, week by week for the next `$weeks` weeks, plus open tickets and work given to contractors. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 113 | `projectCapacity` | read [projects](/code/tables/projects.md) |  |
| 122 | `projectCapacity` | read [projects](/code/tables/projects.md) |  |
| 136 | `projectCapacity` | read [task_time_entries](/code/tables/task_time_entries.md), read [tasks](/code/tables/tasks.md), read [task_statuses](/code/tables/task_statuses.md) |  |
| 140 | `projectCapacity` | read [suppliers](/code/tables/suppliers.md) |  |
| 155 | `projectCapacity` | read [project_members](/code/tables/project_members.md) |  |
| 213 | `projectCapacity` | read [analysts](/code/tables/analysts.md) |  |
| 221 | `projectCapacity` | read [ticket_rota_entries](/code/tables/ticket_rota_entries.md), read [ticket_rota_shifts](/code/tables/ticket_rota_shifts.md) |  |
| 231 | `projectCapacity` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [includes/projects/read.php](/code/files/includes/projects/read.php.md), [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), [includes/task_contractors.php](/code/files/includes/task_contractors.php.md)

**Included by:** [api/projects/capacity.php](/code/files/api/projects/capacity.php.md)

**Calls:** [activeTenantReadFilter()](/code/functions/activeTenantReadFilter.md), [allAccessibleTenantsFilter()](/code/functions/allAccessibleTenantsFilter.md), [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [projectCode()](/code/functions/projectCode.md), [projectEstimatesReady()](/code/functions/projectEstimatesReady.md), [projectSettings()](/code/functions/projectSettings.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [t()](/code/functions/t.md), [tasksContractorReady()](/code/functions/tasksContractorReady.md), [tasksSupplierNameSql()](/code/functions/tasksSupplierNameSql.md)

