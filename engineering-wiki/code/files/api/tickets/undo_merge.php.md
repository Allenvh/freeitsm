# api/tickets/undo_merge.php

> API endpoint - 37 lines

API Endpoint: undo a merge.

POST { merge_id } -> { success, source_ticket_id, source_ticket_number, returned, target_trashed }

Module access, like merging itself. The engine decides what is reversible — see
`unmergeTicket()`, which takes back only what the merge recorded and leaves every
reply written since on the surviving ticket.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/undo_merge.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [unmergeTicket()](/code/functions/unmergeTicket.md)

