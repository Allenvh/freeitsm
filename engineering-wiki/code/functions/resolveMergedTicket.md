# resolveMergedTicket()

Follow the merge chain to the ticket that is actually live.

A ticket merged into a ticket that was later merged again must resolve all the way
through, or an old email reply would land on a ticket that is itself closed and
redirected. Bounded to 10 hops so a cycle — which should be impossible, but a
database is not a proof — cannot hang a mailbox poller.

```php
function resolveMergedTicket(PDO $conn, int $ticketId): int
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** int the id of the live ticket (the input id if it was never merged)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 131 | `resolveMergedTicket` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [findTicketByNumber()](/code/functions/findTicketByNumber.md)

