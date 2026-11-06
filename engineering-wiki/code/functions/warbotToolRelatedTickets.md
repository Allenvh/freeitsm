# warbotToolRelatedTickets()

related_tickets: the tickets linked to a ticket number, with the link type, direction and each one's status.

```php
function warbotToolRelatedTickets(PDO $conn, array $args, int $analystId): string
```

Defined in [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), line 916.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$args` | `array` |  | the model's arguments - untrusted: ticket (the ticket number, e.g. as shown on the ticket) |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 922 | `warbotToolRelatedTickets` | read [tickets](/code/tables/tickets.md) |  |
| 930 | `warbotToolRelatedTickets` | read [ticket_links](/code/tables/ticket_links.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Calls

[ticketAiSubjectSql()](/code/functions/ticketAiSubjectSql.md)

*Nothing calls this function by name.*

