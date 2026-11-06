# api/tickets/save_ticket_priority.php

> API endpoint - 72 lines

API Endpoint: Save ticket priority (create or update)

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_priority.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_PRIORITIES (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 40 |  | **update** [ticket_priorities](/code/tables/ticket_priorities.md) (`is_default`) |  |
| 46 |  | **update** [ticket_priorities](/code/tables/ticket_priorities.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 52 |  | **insert** [ticket_priorities](/code/tables/ticket_priorities.md) (`name`, `colour`, `is_default`, `display_order`, `is_active`) |  |
| 58 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 60 |  | **update** [ticket_priorities](/code/tables/ticket_priorities.md) (`is_default`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wf_emit()](/code/functions/wf_emit.md)

