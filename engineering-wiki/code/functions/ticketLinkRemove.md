# ticketLinkRemove()

Remove a link by id.

```php
function ticketLinkRemove(PDO $conn, int $analystId, int $linkId): array
```

Defined in [includes/ticket_links.php](/code/files/includes/ticket_links.php.md), line 184.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$linkId` | `int` |  | the link (ticket_links.id) to remove |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 186 | `ticketLinkRemove` | read [ticket_links](/code/tables/ticket_links.md) |  |
| 199 | `ticketLinkRemove` | **delete** [ticket_links](/code/tables/ticket_links.md) |  |

## Calls

[analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [ticketLinkAudit()](/code/functions/ticketLinkAudit.md), [ticketLinkNum()](/code/functions/ticketLinkNum.md)

## Called from

- [api/tickets/delete_ticket_link.php](/code/files/api/tickets/delete_ticket_link.php.md)

