# api/tickets/get_ticket_sla.php

> API endpoint - 44 lines

API: Get the SLA state of a single ticket.

Thin wrapper around includes/sla.php's `sla_get_state()`. The same function
will also be used directly from the inbox list-render path (for the
time-to-breach column) — but for the reading pane we hit this endpoint
separately so the page-load isn't blocked by SLA computation for every
ticket that's been rendered server-side.

GET ?ticket_id=N

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_ticket_sla.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/sla.php](/code/files/includes/sla.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sla_get_state()](/code/functions/sla_get_state.md)

