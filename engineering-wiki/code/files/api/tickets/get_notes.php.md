# api/tickets/get_notes.php

> API endpoint - 166 lines

API Endpoint: Get notes for a ticket

## The endpoint

| | |
|---|---|
| URL | `api/tickets/get_notes.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 37 |  | read [ticket_notes](/code/tables/ticket_notes.md), read [analysts](/code/tables/analysts.md) |  |
| 65 |  | read [integration_comment_map](/code/tables/integration_comment_map.md), read [integration_links](/code/tables/integration_links.md), read [integration_connections](/code/tables/integration_connections.md) |  |
| 98 |  | read [document_links](/code/tables/document_links.md), read [documents](/code/tables/documents.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [integrationsCommentSchemaReady()](/code/functions/integrationsCommentSchemaReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

