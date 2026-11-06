# integrationsEntityTenantId()

The tenant_id of a ticket / problem / change, or null.

```php
function integrationsEntityTenantId(PDO $conn, string $entityType, int $entityId): ?int
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 452.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$entityType` | `string` |  | 'ticket', 'problem' or 'change'; anything else gives null |
| `$entityId` | `int` |  | the id of that work item |

**Returns:** `?int`

## Called from

- [api/integrations/connections_for_ticket.php](/code/files/api/integrations/connections_for_ticket.php.md)
- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

