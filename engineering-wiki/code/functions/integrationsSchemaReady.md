# integrationsSchemaReady()

Are the integration tables present?

Every read below goes through this. An install that has not run Database
Verification yet has no tables, and an unguarded query would throw inside the
ticket view — which is how a missing gate once produced an empty inbox.
"Not set up" must look like "no linked issues", never like an error.

```php
function integrationsSchemaReady(PDO $conn): bool
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `integrationsSchemaReady` | read [integration_connections](/code/tables/integration_connections.md) |  |
| 45 | `integrationsSchemaReady` | read [integration_links](/code/tables/integration_links.md) |  |

## Called from

- [api/integrations/connections_for_ticket.php](/code/files/api/integrations/connections_for_ticket.php.md)
- [api/integrations/delete_connection.php](/code/files/api/integrations/delete_connection.php.md)
- [api/integrations/escalate_ticket.php](/code/files/api/integrations/escalate_ticket.php.md)
- [api/integrations/get_mapping.php](/code/files/api/integrations/get_mapping.php.md)
- [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md)
- [api/integrations/save_mapping.php](/code/files/api/integrations/save_mapping.php.md)
- [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md)
- [api/integrations/tracker_options.php](/code/files/api/integrations/tracker_options.php.md)
- [cron/integration_poll.php](/code/files/cron/integration_poll.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsLoadConnection()](/code/functions/integrationsLoadConnection.md), [integrationsListConnections()](/code/functions/integrationsListConnections.md), [integrationsLinksFor()](/code/functions/integrationsLinksFor.md), [integrationsAlreadyLinked()](/code/functions/integrationsAlreadyLinked.md), [integrationsEscalate()](/code/functions/integrationsEscalate.md), [integrationsPullComments()](/code/functions/integrationsPullComments.md), [integrationsRefreshConnection()](/code/functions/integrationsRefreshConnection.md)
- [system/integrations/index.php](/code/files/system/integrations/index.php.md)
- [system/integrations/provider.php](/code/files/system/integrations/provider.php.md)

