# includes/ticket_numbering.php

> PHP - 839 lines

Ticket numbering — the ONE place a ticket number is made or recognised.

⚠️ WHY THIS FILE EXISTS. `generateTicketNumber()` was written THREE times —
in includes/services/tickets.php, api/self-service/create_ticket.php and
api/tickets/check_mailbox_email.php — and the format was hardcoded a fourth,
fifth, sixth and seventh time as the regex `[A-Z]{3}-\d{3}-\d{5}` across four
more files. A setting that reached only one of them would have given portal
tickets one format and emailed tickets another.

🔴 THE LANDMINE THIS FILE DEFUSES. Every notification FreeITSM has ever sent
carries `[SDREF:CKQ-418-73926]` in its subject, and those emails live in
customers' inboxes forever. The inbound parser used to look for that exact
SHAPE. Change the format and every reply to every historical email silently
becomes a new ticket — no error, no warning, across the whole estate at once.
Zammad's own documentation carries the same warning about its numbering.

🔑 The fix is a change of principle: **the tag is a delimiter, the DATABASE is
the authority.** Capture whatever sits between `SDREF:` and `]`, then look it
up. The parser must never encode a format, because there is no longer only
one — an install can change format, and every number it ever issued has to go
on working.

- See: `docs/design/ticket-numbering.md`
## Classes

- [TicketNumbering](/code/classes/TicketNumbering.md) (class, line 30)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 87 | `TicketNumbering::settings` | read [system_settings](/code/tables/system_settings.md) |  |
| 193 | `TicketNumbering::windCounterTo` | **insert** [ticket_number_counters](/code/tables/ticket_number_counters.md) (`counter_key`, `next_value`) |  |
| 265 | `TicketNumbering::typeCode` | read [ticket_types](/code/tables/ticket_types.md) |  |
| 302 | `TicketNumbering::resolveTenant` | read [tenants](/code/tables/tenants.md) |  |
| 330 | `TicketNumbering::companyCode` | read [tenants](/code/tables/tenants.md) |  |
| 337 | `TicketNumbering::companyCode` | read [tenants](/code/tables/tenants.md) |  |
| 395 | `TicketNumbering::codeClashes` | read [tenants](/code/tables/tenants.md) |  |
| 433 | `TicketNumbering::claimNext` | **insert** [ticket_number_counters](/code/tables/ticket_number_counters.md) (`counter_key`, `next_value`) |  |
| 494 | `TicketNumbering::inUse` | read [tickets](/code/tables/tickets.md) |  |
| 500 | `TicketNumbering::inUse` | read [ticket_number_history](/code/tables/ticket_number_history.md) |  |
| 519 | `TicketNumbering::findTicketId` | read [tickets](/code/tables/tickets.md) |  |
| 526 | `TicketNumbering::findTicketId` | read [ticket_number_history](/code/tables/ticket_number_history.md) |  |
| 675 | `TicketNumbering::planRenumber` | read [tickets](/code/tables/tickets.md) |  |
| 782 | `TicketNumbering::assertPlanSafe` | read [ticket_number_history](/code/tables/ticket_number_history.md) |  |
| 812 | `TicketNumbering::applyRenumber` | **insert** [ticket_number_history](/code/tables/ticket_number_history.md) (`ticket_id`, `ticket_number`, `reason`) |  |
| 814 | `TicketNumbering::applyRenumber` | **update** [tickets](/code/tables/tickets.md) (`ticket_number`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md)

**Included by:** [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/system/get_tenants.php](/code/files/api/system/get_tenants.php.md), [api/system/save_tenant.php](/code/files/api/system/save_tenant.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [api/tickets/get_ticket_thread.php](/code/files/api/tickets/get_ticket_thread.php.md), [api/tickets/numbering_preview.php](/code/files/api/tickets/numbering_preview.php.md), [api/tickets/numbering_renumber.php](/code/files/api/tickets/numbering_renumber.php.md), [api/tickets/send_email.php](/code/files/api/tickets/send_email.php.md), [includes/catalogue_approvals.php](/code/files/includes/catalogue_approvals.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), [includes/services/asset_tags.php](/code/files/includes/services/asset_tags.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md)

