# integrationsEntityRouting()

The company, department, type and priority of a work item, for routing.

⚠️ The column is `ticket_type_id`, not `type_id` — the workflow engine's lookup
key is `ticket.type_id`, which is not the column name and is an easy way to
write a query that silently returns nothing.

Only tickets route today; problems and changes return an empty routing set, so
they fall through to whatever the caller supplied.

```php
function integrationsEntityRouting(PDO $conn, string $entityType, int $entityId): array
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$entityType` | `string` |  | 'ticket', 'problem' or 'change'; only 'ticket' returns anything but nulls |
| `$entityId` | `int` |  | the id of that work item |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 1149 | `integrationsEntityRouting` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

