# api/tickets/render_reply_template.php

> API endpoint - 79 lines

API Endpoint: resolve a template's merge codes against a ticket.

POST { template_id, ticket_id } -> { body }

WHY THE SERVER DOES THE SUBSTITUTION
------------------------------------
The picker could have merged client-side from the ticket already loaded in the
inbox — it would have been fewer lines. It would also have put the escaping in
JavaScript, where the next caller (the portal, a future mobile client) would need
its own copy and would eventually get it wrong. Doing it here keeps the one rule in
includes/reply_templates.php, and lets this endpoint re-check two things the client
cannot be trusted to: that the analyst may see this TICKET, and that they may see
this TEMPLATE.

## The endpoint

| | |
|---|---|
| URL | `api/tickets/render_reply_template.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/reply_templates.php](/code/files/includes/reply_templates.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [renderReplyTemplate()](/code/functions/renderReplyTemplate.md), [replyTemplatesVisibleTo()](/code/functions/replyTemplatesVisibleTo.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

