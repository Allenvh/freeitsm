# api/tickets/delete_time_entry.php

> API endpoint - 32 lines

API Endpoint: Soft-delete a ticket time entry (sets is_active = 0). Thin UI adapter over TicketsService::deleteTimeEntry — only the entry's own analyst may delete it (restrictToOwner = true). The entry id alone identifies it; the service resolves + scope-gates the owning ticket.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_time_entry.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

