# api/problem-management/list_linkable_tickets.php

> API endpoint - 63 lines

API: list open incidents (tickets) that can be linked to a problem — scoped to the problem's company, excluding closed/trashed ones and any already linked. Optional ?q search on number/subject. Used by the "Link incident" picker.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/list_linkable_tickets.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Reads | `GET problem_id`, `GET q` |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 27 |  | read [problems](/code/tables/problems.md) |  |
| 41 |  | read [problem_tickets](/code/tables/problem_tickets.md) |  |
| 49 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

