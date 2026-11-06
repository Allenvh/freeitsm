# api/tickets/bulk_update_tickets.php

> API endpoint - 115 lines

API Endpoint: apply one field change to many tickets.

POST { ticket_ids: [1,2,3], fields: { status: 'Resolved' } }
  -> { success, updated, failed: [ { id, error } ] }

WHY THIS IS A LOOP OVER THE SERVICE, NOT A BULK `UPDATE`
-------------------------------------------------------
The obvious implementation — one `UPDATE tickets SET status_id = ? WHERE id IN (…)`
— would be faster and wrong. Changing a ticket is not a column write: it moves the
closed_datetime, syncs the owner, may send a template email, may fire CSAT, and
dispatches workflow triggers. All of that lives in TicketsService::updateTicket(),
and a second write path would drift from it. The symptom of that drift is the worst
kind: a workflow that fires when you change one ticket and silently doesn't when you
change fifty.

So this calls the SAME service method the single-ticket endpoint calls, once per
ticket. Every side effect is identical by construction, and per-ticket access
control comes free — `loadTicket()` inside the service throws for a ticket that is
unknown OR out of this analyst's scope, so a caller cannot reach another company's
tickets by putting their ids in the array.

writeAudit is TRUE here, unlike assign_ticket.php. The single-ticket UI path audits
client-side because it already knows the old value; a bulk caller does not know the
old value of fifty tickets, and asking the browser to fetch them all first would be
both slow and a lie waiting to happen. The service reads the real previous value per
ticket, so the audit trail is correct rather than approximate.

ONE FAILURE DOES NOT STOP THE RUN. A ticket somebody else just deleted, or one in a
status that refuses the change, is reported in `failed[]` and the rest still apply —
an all-or-nothing bulk action that abandons 49 good updates because of one bad row
is not what anybody means by "apply to selection".

## The endpoint

| | |
|---|---|
| URL | `api/tickets/bulk_update_tickets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md), [includes/services/notifications.php](/code/files/includes/services/notifications.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

