# mergeTickets()

Merge one or more source tickets into a target.

```php
function mergeTickets(PDO $conn, int $actorId, array $sourceIds, int $targetId): array
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 336.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$actorId` | `int` |  | the analyst doing the merge, for the audit rows |
| `$sourceIds` | `array` |  | tickets to fold away |
| `$targetId` | `int` |  | the ticket the analyst chose to keep |

**Returns:** array {target_id, target_number, merged: int[], created_new: bool}

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [mergeCreateTargetTicket()](/code/functions/mergeCreateTargetTicket.md), [mergeOneTicket()](/code/functions/mergeOneTicket.md), [mergeSettings()](/code/functions/mergeSettings.md), [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [ticketSensitivityInherit()](/code/functions/ticketSensitivityInherit.md)

## Called from

- [api/tickets/merge_tickets.php](/code/files/api/tickets/merge_tickets.php.md)

