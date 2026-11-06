# ticketLinkNum()

A ticket's number for audit text, falling back to "#id" when it has none (deleted tickets included).

```php
function ticketLinkNum(PDO $conn, int $id): string
```

Defined in [includes/ticket_links.php](/code/files/includes/ticket_links.php.md), line 37.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the ticket whose number to show |

**Returns:** `string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 | `ticketLinkNum` | read [tickets](/code/tables/tickets.md) |  |

## Called from

- [includes/ticket_links.php](/code/files/includes/ticket_links.php.md) - in [ticketLinkCreate()](/code/functions/ticketLinkCreate.md), [ticketLinkRemove()](/code/functions/ticketLinkRemove.md)

