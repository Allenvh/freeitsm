# api/tickets/get_ticket_counts.php

> API endpoint - 601 lines

API Endpoint: Get ticket counts by department and status Returns hierarchical count data for folder view Respects team-based filtering for users with team assignments

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_counts.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 72 |  | read [department_teams](/code/tables/department_teams.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 87 |  | read [tickets](/code/tables/tickets.md) |  |
| 96 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 110 |  | read [tickets](/code/tables/tickets.md) |  |
| 119 |  | read [departments](/code/tables/departments.md), read [tickets](/code/tables/tickets.md) |  |
| 135 |  | read [departments](/code/tables/departments.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 150 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 165 |  | read [tickets](/code/tables/tickets.md) |  |
| 173 |  | read [departments](/code/tables/departments.md), read [tickets](/code/tables/tickets.md) |  |
| 189 |  | read [departments](/code/tables/departments.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 204 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 218 |  | read [tickets](/code/tables/tickets.md) |  |
| 228 |  | read [tickets](/code/tables/tickets.md) |  |
| 235 |  | read [tickets](/code/tables/tickets.md) |  |
| 244 |  | read [tickets](/code/tables/tickets.md) |  |
| 262 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 275 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 286 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 299 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 337 |  | read [analysts](/code/tables/analysts.md), read [tickets](/code/tables/tickets.md) |  |
| 348 |  | read [analysts](/code/tables/analysts.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 386 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 509 |  | read [teams](/code/tables/teams.md), read [tickets](/code/tables/tickets.md) |  |
| 520 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 552 |  | read [tickets](/code/tables/tickets.md) |  |
| 564 |  | read [tickets](/code/tables/tickets.md) |  |
| 573 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), [includes/inbox_view_filter.php](/code/files/includes/inbox_view_filter.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [inboxViewFilterFromRequest()](/code/functions/inboxViewFilterFromRequest.md), [inboxViewFilterSql()](/code/functions/inboxViewFilterSql.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [snoozeHiddenSql()](/code/functions/snoozeHiddenSql.md), [snoozeOnlySql()](/code/functions/snoozeOnlySql.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

## Traps

> [!WARNING]
> **Line 82:** never short-circuit an empty department list to 0 - count "t.department_id IS NULL". Every team member sees no-department tickets (the branch below says so with "OR t.department_id IS NULL"); returning 0 here hid ALL of them, and an install with no departments yet showed 0 everywhere once someone joined a team. Applies to the three other empty-list branches below too. tests/team-counts-no-departments.php

> [!WARNING]
> **Line 366:** decide access with `getAccessibleTenantIds()`, never a copy of its rule in SQL. It unions the analyst's own grants with every team's; a second copy drifts.

