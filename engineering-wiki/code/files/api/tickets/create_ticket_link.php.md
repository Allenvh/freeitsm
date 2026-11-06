# api/tickets/create_ticket_link.php

> API endpoint - 30 lines

API Endpoint: Link one ticket to another (issue #38). POST { source_ticket_id, target_ticket_id, relation } where relation is one of related | duplicate_of | parent_of | child_of, from the source ticket's view.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/create_ticket_link.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/ticket_links.php](/code/files/includes/ticket_links.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketLinkCreate()](/code/functions/ticketLinkCreate.md)

