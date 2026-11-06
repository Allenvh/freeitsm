# api/tickets/delete_ticket_status.php

> API endpoint - 56 lines

API Endpoint: Delete ticket status Refuses if the status is in use by any ticket, or if it's the default.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_status.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 37 |  | read [tickets](/code/tables/tickets.md) |  |
| 45 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 46 |  | **delete** [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

