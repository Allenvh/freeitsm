# api/change-management/unlink_ticket.php

> API endpoint - 30 lines

API: unlink an incident (ticket) from a change. Thin UI adapter over ChangesService::unlinkTicket (company-scope gated). Accepts change_id + ticket_id.

## The endpoint

| | |
|---|---|
| URL | `api/change-management/unlink_ticket.php` |
| Protected by | Module access: changes (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/changes.php](/code/files/includes/services/changes.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

