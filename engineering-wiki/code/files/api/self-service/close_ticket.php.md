# api/self-service/close_ticket.php

> API endpoint - 159 lines

API: close your own ticket from the Self-Service Portal. POST { ticket_id, reason? }

For the commonest wasted ticket on any desk: "it started working", "I sorted
it myself", "I raised this twice". Without this the requester either replies
asking somebody to close it - which costs an analyst a round trip to do
nothing - or says nothing and the ticket ages in a queue.

🔑 IT GOES THROUGH TicketsService::updateTicket LIKE EVERY OTHER CLOSURE.
Closing is not one UPDATE: it stamps closed_datetime, stops the SLA, fires
the ticket_closed template, may trigger the CSAT survey, dispatches the
ticket.closed workflow event, and is refused by a blocking checklist gate. A
second closure path here would get some of that and silently skip the rest.

🔴 Off unless an administrator turns it on (System → Self-service portal),
and the switch is re-checked here - the button being hidden is not a rule.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/close_ticket.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 71 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 91 |  | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 98 |  | read [users](/code/tables/users.md) |  |
| 134 |  | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/self_service_settings.php](/code/files/includes/self_service_settings.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md), [selfServicePortalSettings()](/code/functions/selfServicePortalSettings.md)

