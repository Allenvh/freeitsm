# integrationsResolveIssueType()

Our ticket type → the tracker's issue type. Falls back to the `*` row, so an install can say "everything is a Task" without listing every type.

```php
function integrationsResolveIssueType(array $maps, ?int $ticketTypeId): ?string
```

Defined in [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md), line 1091.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$maps` | `array` |  | the connection's mappings as returned by `integrationsLoadMaps()` |
| `$ticketTypeId` | `?int` |  | the ticket's ticket_type_id; null falls straight to the '*' row |

**Returns:** `?string`

## Called from

- [includes/integrations/integrations.php](/code/files/includes/integrations/integrations.php.md) - in [integrationsEscalate()](/code/functions/integrationsEscalate.md)

