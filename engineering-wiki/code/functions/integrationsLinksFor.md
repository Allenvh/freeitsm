# integrationsLinksFor()

Links for one work item, newest first. Empty when the schema is not ready.

```php
function integrationsLinksFor(PDO $conn, string $entityType, int $entityId): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 476.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$entityType` | `string` |  | 'ticket', 'problem' or 'change' |
| `$entityId` | `int` |  | the id of that work item |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 481 | `integrationsLinksFor` | read [integration_links](/code/tables/integration_links.md), read [integration_connections](/code/tables/integration_connections.md) |  |

## Calls

[integrationsSchemaReady()](/code/functions/integrationsSchemaReady.md)

## Called from

- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)
- [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md) - in `WorkflowEngine::action_send_note_to_tracker`

