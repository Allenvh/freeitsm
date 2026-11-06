# integrationsListConnections()

List connections for the admin UI.

⚠️ DELIBERATELY UNFILTERED by company. This is a CONNECTION-shaped table (the
same shape as messaging_channels and mailboxes), where tenant_id NULL means
SHARED across every company rather than "the Default company's". An admin
configuring routing needs to see every connection at once. Scoping this with
`activeTenantFilter()` would hide every shared connection from every client
company — see Multi-Tenancy-Developer-Guide §1 for all three meanings of NULL.

⚠️ Secrets are NEVER returned. `has_credentials` is a boolean and that is all
the UI gets. A read that hands back credentials needs the same care as a
write, and an unfiltered list that leaked tokens would be a cross-company
credential leak rather than a convenience.

```php
function integrationsListConnections(PDO $conn, bool $activeOnly = false): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 363.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$activeOnly` | `bool` | `false` | true to leave out connections switched off (is_active = 0) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 368 | `integrationsListConnections` | read [integration_connections](/code/tables/integration_connections.md) |  |

## Calls

[integrationsDecodeCredentials()](/code/functions/integrationsDecodeCredentials.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md), [integrationsSettingKeys()](/code/functions/integrationsSettingKeys.md)

## Called from

- [api/integrations/connections_for_ticket.php](/code/files/api/integrations/connections_for_ticket.php.md)
- [api/integrations/list_connections.php](/code/files/api/integrations/list_connections.php.md)

