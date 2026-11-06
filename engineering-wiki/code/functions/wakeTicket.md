# wakeTicket()

Wake a ticket early. Returns true if it was actually asleep, so a caller can stay quiet rather than announcing a wake that woke nothing.

```php
function wakeTicket(PDO $conn, int $ticketId, ?int $analystId, string $why = ''): bool
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 242.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `?int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$why` | `string` | `''` | the audit trail text; '' records 'Woken' |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 246 | `wakeTicket` | read [tickets](/code/tables/tickets.md) |  |
| 252 | `wakeTicket` | **update** [tickets](/code/tables/tickets.md) (`snoozed_until`, `snoozed_at`, `snoozed_by`, `snooze_reason`) |  |

## Calls

[snoozeAudit()](/code/functions/snoozeAudit.md), [snoozeSchemaReady()](/code/functions/snoozeSchemaReady.md)

## Called from

- [api/tickets/wake_ticket.php](/code/files/api/tickets/wake_ticket.php.md)
- [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md) - in [wakeSnoozedTicketOnCustomerReply()](/code/functions/wakeSnoozedTicketOnCustomerReply.md)

