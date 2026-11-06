# api/tickets/wake_ticket.php

> API endpoint - 56 lines

API: wake a sleeping ticket early — the undo half of snooze_ticket.php.

POST { ticket_id | ticket_ids[] } -> { success, woken, ticket_ids[] }

Reports how many were ACTUALLY asleep, so the caller can say "nothing to wake"
rather than claiming to have done something. Waking a ticket that is already
awake is a no-op, not an error: two analysts pressing Wake a second apart
should both see a sensible outcome.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/wake_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [wakeTicket()](/code/functions/wakeTicket.md)

