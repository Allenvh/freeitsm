# api/tickets/delete_ticket_link.php

> API endpoint - 26 lines

API Endpoint: Remove a ticket-to-ticket link (issue #38). POST { link_id }.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/delete_ticket_link.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_links.php](/code/files/includes/ticket_links.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketLinkRemove()](/code/functions/ticketLinkRemove.md)

