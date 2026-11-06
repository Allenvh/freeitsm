# api/tickets/snooze_ticket.php

> API endpoint - 80 lines

API: put one or more tickets to sleep until a time.

POST { ticket_id | ticket_ids[], preset, until_local?, reason? }
  -> { success, snoozed, snoozed_until, failed[] }

Module access only, like assign / status / rename: deciding a ticket can't be
worked until Thursday is everyday desk work, not administration.

The preset is resolved SERVER-SIDE (includes/ticket_snooze.php) in the
analyst's own timezone, so "tomorrow morning" means their morning and the
install's wake hour is applied in one place rather than in each caller.

Bulk: several ids LOOP the same single-ticket path rather than issuing one
UPDATE ... WHERE id IN (...) — every ticket gets its own access check and its
own audit row, and one refusal doesn't take the others down with it.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/snooze_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [resolveSnoozeUntil()](/code/functions/resolveSnoozeUntil.md), [snoozeTicket()](/code/functions/snoozeTicket.md)

