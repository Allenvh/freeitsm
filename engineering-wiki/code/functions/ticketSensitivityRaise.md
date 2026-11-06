# ticketSensitivityRaise()

Make a ticket confidential if it is not already, and say why in the audit trail. Returns true if it changed.

The audit entry has no analyst (NULL): a rule did this, not a person, and the
value names the rule so nobody has to wonder how it got there.

```php
function ticketSensitivityRaise(PDO $conn, int $ticketId, string $reason): bool
```

Defined in [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md), line 83.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$reason` | `string` |  | why, in words; stored in the audit row as "Confidential - <reason>" |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 88 | `ticketSensitivityRaise` | **update** [tickets](/code/tables/tickets.md) (`sensitivity`) |  |
| 94 | `ticketSensitivityRaise` | **insert** [ticket_audit](/code/tables/ticket_audit.md) (`ticket_id`, `analyst_id`, `field_name`, `old_value`, `new_value`, `created_datetime`) |  |

## Calls

[calendarSyncReconcileTicket()](/code/functions/calendarSyncReconcileTicket.md), [ticketSensitivityReady()](/code/functions/ticketSensitivityReady.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/mark_confidential.php](/code/files/api/self-service/mark_confidential.php.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::createTicket`
- [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md) - in [ticketSensitivityApplyDefaults()](/code/functions/ticketSensitivityApplyDefaults.md), [ticketSensitivityInherit()](/code/functions/ticketSensitivityInherit.md)

