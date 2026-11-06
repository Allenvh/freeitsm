# api/tickets/list_linkable_tickets.php

> API endpoint - 28 lines

API Endpoint: Ticket search for the "Link to ticket" picker (issue #38). GET ?source_ticket_id=&q= — scoped to the source ticket's company, excludes the source ticket itself.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/list_linkable_tickets.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET source_ticket_id`, `GET q` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_links.php](/code/files/includes/ticket_links.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketLinkableList()](/code/functions/ticketLinkableList.md)

