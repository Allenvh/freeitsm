# includes/ticket_events.php

> PHP - 100 lines

Ticket lifecycle events — the one place `ticket.created` is announced from.

WHY THIS FILE EXISTS
--------------------
`ticket.created` used to be dispatched from exactly one place,
TicketsService::create(), which is the ANALYST path. The other two ways a
ticket comes into existence both write their own raw INSERT and never went
near that service:

  - api/self-service/create_ticket.php   (the self-service portal)
  - api/tickets/check_mailbox_email.php  (inbound email)

So on a real service desk, where most tickets arrive by email or through the
portal, the event fired for a minority of tickets. Everything downstream
inherited that blind spot: workflows with a `ticket.created` trigger never
ran for those channels, and the notification bell never saw them either.

Rather than add a third and fourth copy of the payload — which would drift —
the payload is built here, once, from the stored row. Every caller gets the
same shape, so a workflow condition on `ticket.department_id` means the same
thing regardless of how the ticket arrived.

⚠️ Two fields cannot come from the row, because they are facts about the ACT
of creating rather than about the ticket, and they legitimately differ per
channel. They are passed in:

  `$createdBy`       analyst id, portal user id, or null for inbound email
                   (nobody signed in created it)
  `$requesterEmail`  supplied where the caller already knows it; otherwise
                   resolved from the linked user

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ticketDispatchCreated()](/code/functions/ticketDispatchCreated.md) | 48 | Announce that a ticket has been created. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `ticketDispatchCreated` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Includes:** [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

**Included by:** [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md), [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [managersNotifyNewTicket()](/code/functions/managersNotifyNewTicket.md)

