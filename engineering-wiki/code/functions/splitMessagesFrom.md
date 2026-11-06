# splitMessagesFrom()

Which messages a split would move, oldest first.

Shared by the preview and the split itself so the dialog can never promise
something different from what happens.

```php
function splitMessagesFrom(PDO $conn, int $ticketId, int $fromEmailId, bool $includeNewer): array
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 49.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$fromEmailId` | `int` |  | the chosen message (emails.id); must be on this ticket or an exception is thrown |
| `$includeNewer` | `bool` |  | true to take every message received at or after it as well; false for just that one |

**Returns:** array rows of [id, subject, from_name, from_address, received_datetime, direction]

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `splitMessagesFrom` | read [emails](/code/tables/emails.md) |  |
| 67 | `splitMessagesFrom` | read [emails](/code/tables/emails.md) |  |

## Called from

- [api/tickets/split_ticket_preview.php](/code/files/api/tickets/split_ticket_preview.php.md)
- [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) - in [splitTicket()](/code/functions/splitTicket.md)

