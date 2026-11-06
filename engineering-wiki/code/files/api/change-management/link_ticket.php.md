# api/change-management/link_ticket.php

> API endpoint - 39 lines

API: link an incident (ticket) to a change. Thin UI adapter over ChangesService::linkTicket (which gates both sides, enforces the same-company rule, and writes the audit row). Accepts change_id and ticket_id|ticket_number.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/link_ticket.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 |  | read [tickets](/code/tables/tickets.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

