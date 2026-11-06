# api/tickets/delete_sla_calendar.php

> API endpoint - 47 lines

API: Delete a business calendar (hard delete; cascade clears hours + holidays).

Refuses to delete a calendar that's still referenced by a ticket_priorities row
(would leave priorities with a dangling SLA target).

POST JSON: { id }

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_sla_calendar.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_SLA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 31 |  | read [ticket_priorities](/code/tables/ticket_priorities.md) |  |
| 39 |  | **delete** [sla_calendars](/code/tables/sla_calendars.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

