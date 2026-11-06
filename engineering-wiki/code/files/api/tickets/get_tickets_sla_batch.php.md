# api/tickets/get_tickets_sla_batch.php

> API endpoint - 64 lines

API: Get SLA state for a batch of tickets in one round-trip.

Used by the inbox to populate per-row SLA indicators after the email list
has rendered. Calls the same `sla_get_state()` engine per ticket — v1 is
O(N * queries_per_ticket) which is fine for the ~50 tickets per inbox page.
If this becomes a perf bottleneck the engine could grow a proper batch
variant that loads lookups (settings / statuses / calendars) once.

GET ?ticket_ids=1,2,3,4

Returns: { sla: { '<ticket_id>': { enabled, response, resolution, ... } } }
Only returns rows for tickets where SLA is enabled (others are silently
omitted to keep the payload small).

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_tickets_sla_batch.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_ids` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/sla.php](/code/files/includes/sla.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sla_get_state()](/code/functions/sla_get_state.md)

