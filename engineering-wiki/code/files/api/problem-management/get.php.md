# api/problem-management/get.php

> API endpoint - 87 lines

API: a single problem with resolved names, linked incidents, linked changes, audit.

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/get.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Reads | `GET id` |
| Called from | [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 22 |  | read [problems](/code/tables/problems.md), read [problem_statuses](/code/tables/problem_statuses.md), read [problem_priorities](/code/tables/problem_priorities.md), read [analysts](/code/tables/analysts.md) |  |
| 37 |  | read [problem_tickets](/code/tables/problem_tickets.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 51 |  | read [change_relations](/code/tables/change_relations.md), read [changes](/code/tables/changes.md), read [change_statuses](/code/tables/change_statuses.md) |  |
| 64 |  | read [problem_audit](/code/tables/problem_audit.md), read [analysts](/code/tables/analysts.md) |  |
| 75 |  | read [problem_notes](/code/tables/problem_notes.md), read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessProblem()](/code/functions/analystCanAccessProblem.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

