# ticketAiMessagesSince()

How many messages have arrived since the summary was written.

```php
function ticketAiMessagesSince(PDO $conn, int $ticketId, ?int $lastEmailId): int
```

Defined in [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), line 207.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$lastEmailId` | `?int` |  | the last emails.id the summary read; null counts every message on the ticket |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 210 | `ticketAiMessagesSince` | read [emails](/code/tables/emails.md) |  |
| 216 | `ticketAiMessagesSince` | read [emails](/code/tables/emails.md) |  |

## Called from

- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)

