# api/system/debug-tools/D018_ticket_folder_counts.php

> API endpoint - 499 lines

Debug Tool D018 — why do the ticket folder counts say 0?

The numbers on the inbox's folder list (All Tickets, each status, each
department, each analyst, Unassigned) come from one endpoint,
api/tickets/get_ticket_counts.php. That endpoint narrows "every ticket" down
through several filters, and any ONE of them can take every badge to zero
while the install is perfectly healthy. This tool walks the same filters one
at a time for a chosen analyst and says which step the tickets disappear at.

🔑 THE COMMONEST CAUSE IS NOT THE DATABASE. An analyst who belongs to a team
gets folders and counts only for the departments that team is linked to
(department_teams), plus tickets with no department and their own. Until
3.7.0 a team linked to NO department counted nothing at all bar their own
folder - "My tickets 153, everything else 0" - even on an install with no
departments yet. The fix is in System → Teams, not in Database Verification.

🔴 ONE COPY OF THE FILTERS. The funnel uses the endpoint's own helpers -
`ticketTenantFilter()`, `snoozeHiddenSql()` - rather than restating them, so a
change to how a filter works changes this report too. When the analyst being
checked is the one running it, the REAL endpoint is also called and its
numbers printed beside the funnel's, so the two can be seen to agree.

READ-ONLY. SELECTs and SHOW COLUMNS only. Nothing is written.

Output: plain text, section-delimited with === HEADERS === for easy skimming.

## The endpoint

| | |
|---|---|
| URL | `api/system/debug-tools/D018_ticket_folder_counts.php` |
| Protected by | Signed-in analyst (session check) (via includes/timezone.php) |
| Reads | `GET analyst` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [addSection()](/code/functions/addSection.md) | 62 | Adds one titled section ("=== TITLE ===" and its lines) to the report; `$body` is a string or an array of lines. |
| [emit_and_exit()](/code/functions/emit_and_exit.md) | 72 | Prints every section, separated by blank lines, and stops - the end of the report, and the early exit when a check cannot go on. |
| [d018Columns()](/code/functions/d018Columns.md) | 84 | The columns of one table, or null when the table does not exist. |
| [d018Count()](/code/functions/d018Count.md) | 99 | Run one COUNT(*) and return the number, or the database's own error text. |
| [d018RunRealEndpoint()](/code/functions/d018RunRealEndpoint.md) | 120 | Call the real counts endpoint as the signed-in analyst and return its decoded answer. Runs it in this request (no HTTP round trip), from its own folder so its relative require paths resolve, inside a function so its variables stay out of this file's. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 156 |  | read [analysts](/code/tables/analysts.md) |  |
| 234 |  | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 241 |  | read [department_teams](/code/tables/department_teams.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 250 |  | read [tickets](/code/tables/tickets.md) |  |
| 252 |  | read [tickets](/code/tables/tickets.md) |  |
| 254 |  | read [tickets](/code/tables/tickets.md) |  |
| 256 |  | read [tickets](/code/tables/tickets.md) |  |
| 259 |  | read [tickets](/code/tables/tickets.md) |  |
| 265 |  | read [tickets](/code/tables/tickets.md) |  |
| 269 |  | read [tickets](/code/tables/tickets.md) |  |
| 288 |  | read [tickets](/code/tables/tickets.md) |  |
| 309 |  | read [tickets](/code/tables/tickets.md) |  |
| 317 |  | read [teams](/code/tables/teams.md) |  |
| 329 |  | read [department_teams](/code/tables/department_teams.md), read [departments](/code/tables/departments.md) |  |
| 356 |  | read [tickets](/code/tables/tickets.md), read [departments](/code/tables/departments.md) |  |
| 376 |  | read [teams](/code/tables/teams.md) |  |
| 383 |  | read [analyst_teams](/code/tables/analyst_teams.md), read [analysts](/code/tables/analysts.md) |  |
| 388 |  | read [department_teams](/code/tables/department_teams.md), read [departments](/code/tables/departments.md) |  |
| 398 |  | read [tickets](/code/tables/tickets.md), read [department_teams](/code/tables/department_teams.md) |  |
| 412 |  | read [departments](/code/tables/departments.md), read [department_teams](/code/tables/department_teams.md) |  |
| 416 |  | read [analysts](/code/tables/analysts.md), read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 420 |  | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |
| 421 |  | read [analyst_teams](/code/tables/analyst_teams.md), read [analysts](/code/tables/analysts.md) |  |
| 422 |  | read [department_teams](/code/tables/department_teams.md), read [teams](/code/tables/teams.md) |  |
| 423 |  | read [department_teams](/code/tables/department_teams.md), read [departments](/code/tables/departments.md) |  |
| 431 |  | read [analyst_teams](/code/tables/analyst_teams.md), read [teams](/code/tables/teams.md) |  |
| 446 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 451 |  | read [tickets](/code/tables/tickets.md) |  |
| 452 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 454 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md)

**Calls:** [analystCanAccessModule()](/code/functions/analystCanAccessModule.md), [analystIsAdmin()](/code/functions/analystIsAdmin.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [snoozeHiddenSql()](/code/functions/snoozeHiddenSql.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

## Traps

> [!WARNING]
> **Line 108:** only ever for the analyst who is signed in. Running it for somebody else would mean putting their id into the admin's live session, and a fatal error part-way through would leave the admin signed in as them.

