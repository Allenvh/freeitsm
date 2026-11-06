# api/tickets/delete_ticket_priority.php

> API endpoint - 54 lines

API Endpoint: Delete ticket priority Refuses if the priority is in use by any ticket, or if it's the default.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_priority.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_PRIORITIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 30 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 35 |  | read [tickets](/code/tables/tickets.md) |  |
| 43 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 44 |  | **delete** [ticket_priorities](/code/tables/ticket_priorities.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

