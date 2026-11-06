# includes/ticket_reply.php

> PHP - 112 lines

Reopening a finished ticket when the customer comes back.

WHY THIS IS SHARED
------------------
A customer can come back through more than one door — they can reply in the
self-service portal, or reply to the notification email. "Reply to a finished
ticket" means the same thing whichever door they used, so the rule lives here
once and both callers use it. Two copies would drift, and the symptom would be
a customer whose reply reopens their ticket on Monday (email) but not on
Tuesday (portal), which is impossible to explain to them.

The email template FreeITSM sends already tells requesters "just reply to this
email and it will be reopened" (workflow/includes/templates.php) — before this,
nothing anywhere honoured that promise on any channel.

WHAT COUNTS AS "FINISHED"
-------------------------
`ticket_statuses.is_closed` — the app's own definition of a finished ticket,
configurable per install in Tickets → Settings → Statuses. We deliberately do
NOT hardcode status names: an install that renames "Closed" to "Completed", or
marks "Resolved" as closed too, gets the behaviour it configured. Note the
shipped default flags only "Closed" and not "Resolved", so out of the box a
reply to a Resolved ticket appends without reopening.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [customerReplyReopensTickets()](/code/functions/customerReplyReopensTickets.md) | 39 | Is reopen-on-customer-reply switched on? Tickets → Settings → General. |
| [reopenTicketForCustomerReply()](/code/functions/reopenTicketForCustomerReply.md) | 69 | Reopen a ticket because its requester replied. No-op unless the setting is on AND the ticket is currently in a status flagged is_closed. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `customerReplyReopensTickets` | read [system_settings](/code/tables/system_settings.md) |  |
| 77 | `reopenTicketForCustomerReply` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 92 | `reopenTicketForCustomerReply` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 104 | `reopenTicketForCustomerReply` | **update** [tickets](/code/tables/tickets.md) (`status_id`, `closed_datetime`, `updated_datetime`) |  |

## How it hangs together

**Includes:** [includes/functions.php](/code/files/includes/functions.php.md)

**Included by:** [api/self-service/reply_ticket.php](/code/files/api/self-service/reply_ticket.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [includes/messaging/ingest.php](/code/files/includes/messaging/ingest.php.md), [includes/webchat/webchat.php](/code/files/includes/webchat/webchat.php.md)

## Traps

> [!WARNING]
> **Line 100, in `reopenTicketForCustomerReply`:** leaving it set made a reopened ticket still count as closed everywhere that reads closed_datetime - reports, and the chat lookup, which would open a NEW ticket for the customer's next message rather than continue the one just reopened.

