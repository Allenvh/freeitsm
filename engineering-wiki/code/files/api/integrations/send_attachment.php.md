# api/integrations/send_attachment.php

> API endpoint - 127 lines

Send ONE of a ticket's attachments to an issue it is already linked to.

The gap this fills: attachments only travelled at the moment of escalation,
so the screenshot that turns up *after* a developer asks "can you send me the
error?" — which is when it usually turns up — had nowhere to go.

Deliberately one file, chosen by a person, rather than "push everything new
automatically". An attachment cannot be unsent, and somebody attaching a
screenshot to a ticket is usually replying to a colleague, not publishing to
the dev team. The decision stays with the analyst.

Body: { attachment_id, link_id }

## The endpoint

| | |
|---|---|
| URL | `api/integrations/send_attachment.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 47 |  | read [integration_links](/code/tables/integration_links.md) |  |
| 110 |  | **insert** [ticket_notes](/code/tables/ticket_notes.md) (`ticket_id`, `analyst_id`, `note_text`, `is_internal`, `created_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md), [integrationsSendAttachments()](/code/functions/integrationsSendAttachments.md), [integrationsTicketAttachments()](/code/functions/integrationsTicketAttachments.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

