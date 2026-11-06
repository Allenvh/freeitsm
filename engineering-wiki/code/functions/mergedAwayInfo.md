# mergedAwayInfo()

Is this ticket a merged-away one? Returns the target row, or null.

```php
function mergedAwayInfo(PDO $conn, int $ticketId): ?array
```

Defined in [includes/ticket_merge.php](/code/files/includes/ticket_merge.php.md), line 301.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 307 | `mergedAwayInfo` | read [tickets](/code/tables/tickets.md), read [ticket_merges](/code/tables/ticket_merges.md) |  |

## Called from

- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)

