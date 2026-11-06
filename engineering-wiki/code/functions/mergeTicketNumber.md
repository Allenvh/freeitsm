# mergeTicketNumber()

A ticket's number for audit and note text; "#id" if the lookup fails.

```php
function mergeTicketNumber(PDO $conn, int $ticketId): string
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 630.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 632 | `mergeTicketNumber` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md) - in [unmergeTicket()](/code/functions/unmergeTicket.md), [mergeOneTicket()](/code/functions/mergeOneTicket.md)

