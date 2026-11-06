# includes/ticket_views.php

> PHP - 148 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Ticket Views Developer Guide](https://github.com/edmozley/freeitsm/wiki/Ticket-Views-Developer-Guide)

Who has looked at a ticket (discussion #62, step 2).

WHY
---
Managers are coming to the self-service portal. If a manager can read the
tickets of the people they manage, those people are owed the knowledge of
WHEN they did. So the requester sees, on their own ticket, every time a
manager opened it - shown by default, not behind a button.

WHAT THE REQUESTER DOES NOT SEE: service-desk views. Ed's reasoning: "Viewed
by the service desk, Monday 09:02" invites "why did you look at my ticket and
not do anything about it", which is pressure the desk should not be under for
doing its job. Analysts' views are recorded all the same, and appear in the
ticket's audit trail for analysts.

STORAGE
-------
`ticket_views`, one row per viewer per UTC day, kept by the unique key
uq_ticket_views_day - opening a ticket twenty times is one row with
view_count 20. Its own table rather than ticket_audit, because the audit
trail's actor can only be an analyst and a manager reading in the portal is
not one; and because views are many times more frequent than changes.

⚠️ The unique key is what makes the upsert below an upsert. Database
Verification creates tables without constraints, so it is restored from
db_verify_indexes.php - without it every view would be a new row.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [ticketViewsReady()](/code/functions/ticketViewsReady.md) | 33 | Who has looked at a ticket (discussion #62, step 2). |
| [ticketViewRecord()](/code/functions/ticketViewRecord.md) | 54 | Record that someone opened a ticket. Never throws: a view that fails to be recorded must not stop the ticket from opening. |
| [ticketViewsAsAuditRows()](/code/functions/ticketViewsAsAuditRows.md) | 82 | Views shaped as audit-trail rows, so the analyst's Audit window (desktop and phone alike, both fed by api/tickets/get_ticket_audit.php) shows them with no change of its own. One row per viewer per day, dated at their LAST view. |
| [ticketViewsForRequester()](/code/functions/ticketViewsForRequester.md) | 130 | Portal viewers of a ticket OTHER than its requester, newest first - what the requester is shown under "Who has seen this ticket". Analysts are deliberately absent (see the header). |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 38 | `ticketViewsReady` | read [ticket_views](/code/tables/ticket_views.md) |  |
| 64 | `ticketViewRecord` | **insert** [ticket_views](/code/tables/ticket_views.md) (`ticket_id`, `viewer_type`, `viewer_id`, `view_date`, `first_viewed_datetime`, `last_viewed_datetime`, `view_count`) |  |
| 88 | `ticketViewsAsAuditRows` | read [ticket_views](/code/tables/ticket_views.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |
| 136 | `ticketViewsForRequester` | read [ticket_views](/code/tables/ticket_views.md), read [users](/code/tables/users.md) |  |

## How it hangs together

**Included by:** [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md), [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md), [api/tickets/get_ticket_audit.php](/code/files/api/tickets/get_ticket_audit.php.md), [includes/managers.php](/code/files/includes/managers.php.md)

