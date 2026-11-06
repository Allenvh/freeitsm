# splitInfoFor()

Splits recorded against a ticket, in both directions, for the banner.

Undone splits are excluded: the messages are back where they started, so a banner
announcing a split that no longer exists would be a lie. The row stays in the table
as history.

```php
function splitInfoFor(PDO $conn, int $ticketId): array
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 361.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 364 | `splitInfoFor` | read [ticket_splits](/code/tables/ticket_splits.md) |  |
| 370 | `splitInfoFor` | read [ticket_splits](/code/tables/ticket_splits.md) |  |

## Called from

- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)

