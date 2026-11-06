# ticketAiLatestSummary()

The newest stored summary (or briefing) for a ticket, or null.

```php
function ticketAiLatestSummary(PDO $conn, int $ticketId, string $kind = 'summary'): ?array
```

Defined in [includes/ticket_ai.php](/code/files/includes/ticket_ai.php.md), line 229.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$kind` | `string` | `'summary'` | 'summary' or 'read'; anything else is treated as 'summary' |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 235 | `ticketAiLatestSummary` | read [ticket_ai_summaries](/code/tables/ticket_ai_summaries.md), read [analysts](/code/tables/analysts.md) |  |

## Called from

- [api/tickets/ai_read.php](/code/files/api/tickets/ai_read.php.md)
- [api/tickets/ai_summary.php](/code/files/api/tickets/ai_summary.php.md)

