# api/tickets/save_priority_sla.php

> API endpoint - 53 lines

API: Save the SLA fields on a ticket_priorities row (sla_response_minutes, sla_resolution_minutes, sla_calendar_id).

POST JSON: { id, sla_response_minutes, sla_resolution_minutes, sla_calendar_id }

Doesn't touch the other ticket_priorities columns (name, colour, etc.) — that's
a different endpoint. Pass nulls for minutes to clear targets; pass null
sla_calendar_id to detach the calendar.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_priority_sla.php` |
| Protected by | Module access: tickets (requireModuleAccessJson); Permission: Cap::TICKETS_SLA (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 |  | read [sla_calendars](/code/tables/sla_calendars.md) |  |
| 43 |  | **update** [ticket_priorities](/code/tables/ticket_priorities.md) (`sla_response_minutes`, `sla_resolution_minutes`, `sla_calendar_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

