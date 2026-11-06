# splitMarkerEmailIds()

The email ids that are split MARKERS on this ticket — the "N messages moved to X" placeholders left behind by an earlier split.

They are real rows in `emails`, but they are not content: an analyst cannot move a
marker off on its own, and — crucially — a marker must not count towards "would this
leave the ticket empty?". A ticket holding nothing but a marker is still empty of the
real conversation. Markers from splits that were later undone have been deleted, so a
stale id here simply matches no row.

```php
function splitMarkerEmailIds(PDO $conn, int $ticketId): array
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 87.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 90 | `splitMarkerEmailIds` | read [ticket_splits](/code/tables/ticket_splits.md) |  |

## Called from

- [api/tickets/split_ticket_preview.php](/code/files/api/tickets/split_ticket_preview.php.md)
- [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) - in [splitMovableCount()](/code/functions/splitMovableCount.md), [splitMessagesByIds()](/code/functions/splitMessagesByIds.md)

