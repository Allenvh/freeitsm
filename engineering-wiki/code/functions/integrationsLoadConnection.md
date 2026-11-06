# integrationsLoadConnection()

Load a connection with its secrets decrypted. Returns null if missing or if the schema is not there yet.

```php
function integrationsLoadConnection(PDO $conn, $connectionId): ?array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 324.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$connectionId` |  |  | the integration_connections id (cast to int) |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 328 | `integrationsLoadConnection` | read [integration_connections](/code/tables/integration_connections.md) |  |

## Calls

[decryptValue()](/code/functions/decryptValue.md), [integrationsDecodeCredentials()](/code/functions/integrationsDecodeCredentials.md), [integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

## Called from

- [api/integrations/save_connection.php](/code/files/api/integrations/save_connection.php.md)
- [api/integrations/save_mapping.php](/code/files/api/integrations/save_mapping.php.md)
- [api/integrations/send_attachment.php](/code/files/api/integrations/send_attachment.php.md)
- [api/integrations/test_connection.php](/code/files/api/integrations/test_connection.php.md)
- [api/integrations/tracker_options.php](/code/files/api/integrations/tracker_options.php.md)
- [cron/integration_poll.php](/code/files/cron/integration_poll.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_note_to_tracker`

