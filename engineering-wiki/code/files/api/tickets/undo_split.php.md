# api/tickets/undo_split.php

> API endpoint - 37 lines

API Endpoint: undo a split.

POST { split_id } -> { success, source_ticket_id, returned, new_ticket_number }

Module access, like the split itself. The engine refuses on its own terms when the
new ticket has been worked on since — see `undoSplit()` for why refusing beats
guessing.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/undo_split.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [undoSplit()](/code/functions/undoSplit.md)

