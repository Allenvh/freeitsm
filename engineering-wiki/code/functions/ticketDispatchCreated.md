# ticketDispatchCreated()

Announce that a ticket has been created.

Safe to call from anywhere: it never throws. A failure to announce must not
cost somebody their ticket, which is the same rule every existing dispatch
call site already applies with its own try/catch.

```php
function ticketDispatchCreated(
    PDO $conn,
    int $ticketId,
    ?int $createdBy = null,
    ?string $requesterEmail = null
): void
```

Defined in [includes/ticket_events.php](/code/files/includes/ticket_events.php.md), line 48.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | the ticket that now exists |
| `$createdBy` | `?int` | `null` | who performed the creation, if anyone |
| `$requesterEmail` | `?string` | `null` | overrides the address resolved from user_id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `ticketDispatchCreated` | read [tickets](/code/tables/tickets.md), read [users](/code/tables/users.md) |  |

## Calls

[managersNotifyNewTicket()](/code/functions/managersNotifyNewTicket.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)
- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::createTicket`

