# api/integrations/connections_for_ticket.php

> API endpoint - 56 lines

The tracker connections a given ticket may actually be escalated to.

Separate from list_connections.php on purpose: that one is admin-only and
describes connections for configuration. This one is for an ANALYST choosing
where to send a ticket, so it needs the tickets module rather than admin — and
therefore returns the bare minimum, never a hint of a credential.

⚠️ Filtered by the company guard, deliberately. A shared connection serves
everyone; a pinned one only its own company. Showing an analyst a connection
that `integrationsEscalate()` would then refuse is a worse experience than not
offering it — and this is the UI half of the rule, never the enforcement.
The enforcement is still in the service, because this list is only a
suggestion and the escalate endpoint cannot trust its own UI.

## The endpoint

| | |
|---|---|
| URL | `api/integrations/connections_for_ticket.php` |
| Protected by | Module access: tickets (requireModuleAccessJson) |
| Reads | `GET ticket_id` |
| Called from | [assets/js/inbox.js](/code/files/assets/js/inbox.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [integrationsCompaniesCompatible()](/code/functions/integrationsCompaniesCompatible.md), [integrationsEntityTenantId()](/code/functions/integrationsEntityTenantId.md), [integrationsListConnections()](/code/functions/integrationsListConnections.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

