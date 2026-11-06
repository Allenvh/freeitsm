# api/tickets/save_ticket_subject.php

> API endpoint - 68 lines

API: rename a ticket's subject.

POST { ticket_id, subject } -> { success, subject }

The subject is set at intake / creation (and by split) and had no edit path until
now. Everything on screen — the list, the reading-pane header, triage — reads
`tickets.subject`, so this one column is all that changes.

Module access only (like assign / status changes): editing a ticket is everyday
work, not administration. Deliberately does NOT bump `updated_datetime` — fixing a
subject is a correction, not activity, and re-sorting the inbox to the top as though
the customer had replied would be misleading. The change is recorded in the audit
trail instead.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/save_ticket_subject.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 |  | read [tickets](/code/tables/tickets.md) |  |
| 54 |  | **update** [tickets](/code/tables/tickets.md) (`subject`) |  |
| 59 |  | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

