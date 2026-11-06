# api/problem-management/list.php

> API endpoint - 58 lines

API: list problems for the active company, with per-status counts. Filters: ?status_id=, ?q= (title / problem number). Company-scoped.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/list.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Reads | `GET status_id`, `GET q` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md), [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 28 |  | read [problem_tickets](/code/tables/problem_tickets.md), read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md), read [problem_priorities](/code/tables/problem_priorities.md), read [analysts](/code/tables/analysts.md) |  |
| 46 |  | read [problem_statuses](/code/tables/problem_statuses.md) |  |
| 47 |  | read [problems](/code/tables/problems.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketTenantFilter()](/code/functions/ticketTenantFilter.md)

