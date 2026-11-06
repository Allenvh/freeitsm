# api/tickets/save_ticket_status.php

> API endpoint - 87 lines

API Endpoint: Save ticket status (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_status.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_STATUSES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 42 |  | read [tickets](/code/tables/tickets.md) |  |
| 54 |  | **update** [ticket_statuses](/code/tables/ticket_statuses.md) (`is_default`) |  |
| 60 |  | **update** [ticket_statuses](/code/tables/ticket_statuses.md) (`name`, `colour`, `is_closed`, `pauses_sla`, `is_default`, `display_order`, `is_active`) |  |
| 66 |  | **insert** [ticket_statuses](/code/tables/ticket_statuses.md) (`name`, `colour`, `is_closed`, `pauses_sla`, `is_default`, `display_order`, `is_active`) |  |
| 73 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 75 |  | **update** [ticket_statuses](/code/tables/ticket_statuses.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

