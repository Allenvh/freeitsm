# recordPreviewTicket()

── Ticket ──────────────────────────────────────────────────────────────────

```php
function recordPreviewTicket(PDO $conn, int $analystId, int $id): ?array
```

Defined in [includes/record_preview.php](/code/files/includes/record_preview.php.md), line 131.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the tickets id |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 141 | `recordPreviewTicket` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md), read [ticket_priorities](/code/tables/ticket_priorities.md), read [analysts](/code/tables/analysts.md), read [users](/code/tables/users.md) |  |

## Calls

[analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [rpField()](/code/functions/rpField.md), [rpFields()](/code/functions/rpFields.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

