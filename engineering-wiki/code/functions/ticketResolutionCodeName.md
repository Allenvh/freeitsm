# ticketResolutionCodeName()

One resolution code's name, ignoring is_active.

Same reasoning as ticketCategoryPathLabel: a closed ticket keeps the label it
was closed with, whatever has happened to the list since.

```php
function ticketResolutionCodeName(PDO $conn, ?int $codeId): ?string
```

Defined in [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md), line 354.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$codeId` | `?int` |  | the resolution code (ticket_resolution_codes.id); null or 0 gives null |

**Returns:** `?string`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 360 | `ticketResolutionCodeName` | read [ticket_resolution_codes](/code/tables/ticket_resolution_codes.md) |  |

## Called from

- [includes/services/tickets.php](/code/files/includes/services/tickets.php.md) - in `TicketsService::updateTicket`

