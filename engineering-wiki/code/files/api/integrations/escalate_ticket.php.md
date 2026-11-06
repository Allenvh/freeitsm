# api/integrations/escalate_ticket.php

> API endpoint - 207 lines

Escalate a ticket to an external tracker — the MANUAL path.

The second entry point into `integrationsEscalate()`, the first being the
workflow action. Deliberately not a second implementation: every guard,
including the company check, lives in the service so there is exactly one copy
to get right.

Two modes:
  preview = 1  → build the description and hand it back WITHOUT touching Jira.
  otherwise    → create the issue and record the link.

⚠️ The preview matters. This is a one-way door into a system we do not
control: once the issue exists we cannot unsend it, and its contents are
visible to everyone with access to that tracker. The analyst sees the exact
text first.

⚠️ Internal notes never cross. This builds the body from the ticket's public
request text only — `ticket_notes.is_internal` exists for a reason, and "we
should just refund this one" landing on a shared dev board is the kind of bug
that ends a customer relationship.

## The endpoint

| | |
|---|---|
| URL | `api/integrations/escalate_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 83 |  | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [ticket_types](/code/tables/ticket_types.md) |  |
| 108 |  | read [emails](/code/tables/emails.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Calls:** [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [integrationsAbsoluteUrl()](/code/functions/integrationsAbsoluteUrl.md), [integrationsBodyToText()](/code/functions/integrationsBodyToText.md), [integrationsEscalate()](/code/functions/integrationsEscalate.md), [integrationsFormatBytes()](/code/functions/integrationsFormatBytes.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md), [integrationsTicketAttachments()](/code/functions/integrationsTicketAttachments.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [ticketIsConfidential()](/code/functions/ticketIsConfidential.md)

