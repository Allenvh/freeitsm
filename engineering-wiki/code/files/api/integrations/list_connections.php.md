# api/integrations/list_connections.php

> API endpoint - 51 lines

List integration connections for the settings screen.

⚠️ DELIBERATELY UNFILTERED BY COMPANY. integration_connections is a
CONNECTION-shaped table (tenant_id NULL = shared with every company, set =
pinned to one) — the same shape as messaging_channels and mailboxes. An admin
configuring routing needs to see every connection at once. Scoping this with
`activeTenantFilter()` would treat NULL as Default-owned and hide every shared
connection from every client company.

⚠️ WHICH IS EXACTLY WHY THIS MUST NOT LEAK SECRETS. A read that hands back
credentials needs the same care as a write, so `integrationsListConnections()`
returns has_credentials as a boolean and never the token itself — the same rule
api/messaging/get_channels.php follows. An unfiltered list that serialised
tokens would be a cross-company credential leak, not a convenience.

## The endpoint

| | |
|---|---|
| URL | `api/integrations/list_connections.php` |
| Protected by | Administrators only (admin_api_guard.php) |
| Reads | `GET provider` |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/admin_api_guard.php](/code/files/includes/admin_api_guard.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [getAllTenants()](/code/functions/getAllTenants.md), [integrationsListConnections()](/code/functions/integrationsListConnections.md), [isMultiTenant()](/code/functions/isMultiTenant.md)

