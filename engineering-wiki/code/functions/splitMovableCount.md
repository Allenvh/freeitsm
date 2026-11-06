# splitMovableCount()

How many movable (non-marker) messages a ticket has. This is the number that decides whether a split would empty it — see splitMarkerEmailIds for why markers don't count.

```php
function splitMovableCount(PDO $conn, int $ticketId): int
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 104.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 108 | `splitMovableCount` | read [emails](/code/tables/emails.md) |  |

## Calls

[splitMarkerEmailIds()](/code/functions/splitMarkerEmailIds.md)

## Called from

- [api/tickets/split_ticket_preview.php](/code/files/api/tickets/split_ticket_preview.php.md)
- [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) - in [splitTicket()](/code/functions/splitTicket.md)

