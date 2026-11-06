# api/self-service/get_ticket_detail.php

> API endpoint - 287 lines

API: Get Ticket Detail for Self-Service User GET ?ticket_id=X - Returns ticket info, email thread, and non-internal notes

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_ticket_detail.php` |
| Protected by | Signed-in analyst (session check) (via includes/service_context.php) |
| Reads | `GET ticket_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 48 |  | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [departments](/code/tables/departments.md) |  |
| 81 |  | read [users](/code/tables/users.md) |  |
| 128 |  | read [users](/code/tables/users.md) |  |
| 141 |  | read [emails](/code/tables/emails.md) |  |
| 172 |  | read [email_attachments](/code/tables/email_attachments.md) |  |
| 201 |  | read [ticket_notes](/code/tables/ticket_notes.md), read [analysts](/code/tables/analysts.md) |  |
| 222 |  | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |
| 255 |  | read [ticket_recordings](/code/tables/ticket_recordings.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/portal_visibility.php](/code/files/includes/portal_visibility.php.md), [includes/managers.php](/code/files/includes/managers.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), [includes/ticket_views.php](/code/files/includes/ticket_views.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [portalEmailVisibility()](/code/functions/portalEmailVisibility.md), [portalThirdPartyPolicy()](/code/functions/portalThirdPartyPolicy.md), [portalTicketAccess()](/code/functions/portalTicketAccess.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md), [ticketViewRecord()](/code/functions/ticketViewRecord.md), [ticketViewsForRequester()](/code/functions/ticketViewsForRequester.md), [ticketViewsReady()](/code/functions/ticketViewsReady.md)

