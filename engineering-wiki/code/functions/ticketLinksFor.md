# ticketLinksFor()

All of a ticket's links, grouped for display from THIS ticket's perspective. Returns parent (0/1), children[], duplicate_of (0/1), duplicates[], related[].

```php
function ticketLinksFor(PDO $conn, int $ticketId): array
```

Defined in [includes/ticket_links.php](/code/files/includes/ticket_links.php.md), line 211.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 214 | `ticketLinksFor` | read [ticket_links](/code/tables/ticket_links.md), read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |

## Called from

- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)

