# ticketLinkLoad()

Load the minimal ticket row we need (or null if missing/deleted).

```php
function ticketLinkLoad(PDO $conn, int $id): ?array
```

Defined in [includes/ticket_links.php](/code/files/includes/ticket_links.php.md), line 24.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the ticket to load; a deleted ticket gives null |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 26 | `ticketLinkLoad` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) - in [ticketLinkCreate()](/code/functions/ticketLinkCreate.md), [ticketLinkableList()](/code/functions/ticketLinkableList.md)

