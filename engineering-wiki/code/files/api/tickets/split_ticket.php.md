# api/tickets/split_ticket.php

> API endpoint - 58 lines

API Endpoint: split messages off a ticket into a new one.

POST { ticket_id, email_ids: [int], subject? }              // ticked messages
POST { ticket_id, from_email_id, include_newer: bool, subject? }  // legacy anchor
  -> { success, new_ticket_id, new_ticket_number, moved }

The dialog sends email_ids — the exact messages the analyst ticked. The anchor form
is kept so an older cached client still works.

Module access only, like merging: splitting is everyday service-desk work, not
administration. There is no settings tab for it either — unlike a merge, a split
cannot orphan a reference the customer already holds, so there is no install-wide
policy to decide. Both tickets come out live and independent.

The engine is includes/ticket_split.php, where the reasoning lives.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/split_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/ticket_split.php](/code/files/includes/ticket_split.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [splitTicket()](/code/functions/splitTicket.md)

