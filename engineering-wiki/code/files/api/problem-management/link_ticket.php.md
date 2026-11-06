# api/problem-management/link_ticket.php

> API endpoint - 45 lines

API: link an incident (ticket) to a problem. Accepts problem_id|problem_number and ticket_id|ticket_number. Thin UI adapter over ProblemsService::linkTicket (which gates both sides, enforces same-company, and writes the audit note).

## The endpoint

| | |
|---|---|
| URL | `api/problem-management/link_ticket.php` |
| Protected by | Module access: problems (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md), [assets/js/problem-management.js](/code/files/assets/js/problem-management.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 24 |  | read [problems](/code/tables/problems.md) |  |
| 32 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/problems.php](/code/files/includes/services/problems.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

