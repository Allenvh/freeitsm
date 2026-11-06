# api/tickets/bulk_delete_tickets.php

> API endpoint - 68 lines

API Endpoint: move many tickets to the trash.

POST { ticket_ids: [1,2,3] } -> { success, deleted, failed: [ { id, error } ] }

Same shape and the same reasoning as bulk_update_tickets.php: a loop over
TicketsService::deleteTicket() rather than a bulk statement, so the soft-delete
semantics, the audit row and the per-ticket scope check are identical to deleting
one ticket. `true` is the writeAudit flag — the single-ticket UI path audits delete
server-side too, so this matches it exactly.

This is the SOFT delete (the trash), which is what makes it a reasonable bulk
action at all: the recovery story for "I selected the wrong forty" is Restore, not
a database backup.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/bulk_delete_tickets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

