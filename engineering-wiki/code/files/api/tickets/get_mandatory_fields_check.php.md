# api/tickets/get_mandatory_fields_check.php

> API endpoint - 105 lines

API Endpoint: which mandatory fields would be empty if these tickets closed now.

GET ?ticket_id=N
  -> { success, mode: "warn"|"notify"|"block", record: bool,
       missing: [ { key, label } ],
       tickets: [ { ticket_id, ticket_number, mode, record, missing } ] }

GET ?ticket_ids=1,2,3        (bulk actions; at most MAX_IDS)
  -> { success, tickets: [ ... ] }   only the tickets with something missing

Asked by the inbox the moment an analyst closes a ticket - from the status
dropdown, the right-click menu, a drag onto a closed status or a bulk action -
so it can say "close anyway?" or "fill these in first" BEFORE sending the
close. The server enforces the rule again on the close itself
(TicketsService::updateTicket), so this is only the conversation - never the
control. If it fails, the inbox says nothing and lets the server decide,
rather than turning a failed question into a refusal nobody can clear.

mode and record are reported per ticket because they resolve per company.
A ticket that is out of the analyst's scope is simply left out.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_mandatory_fields_check.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id`, `GET ticket_ids` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/i18n.php](/code/files/includes/i18n.php.md), [includes/services/tickets.php](/code/files/includes/services/tickets.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [t()](/code/functions/t.md)

