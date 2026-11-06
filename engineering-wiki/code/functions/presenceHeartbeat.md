# presenceHeartbeat()

Record that this analyst is on this ticket. Upserts onto the (ticket, analyst) unique key, so a ticket left open all afternoon is one row, not a thousand.

`$composing` is the state worth warning about — the reply, forward or note
composer being open — as opposed to merely having the ticket on screen.

```php
function presenceHeartbeat(PDO $conn, int $ticketId, int $analystId, bool $composing): void
```

Defined in [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md), line 78.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$composing` | `bool` |  | true while the reply, forward or note composer is open; false when only viewing |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 82 | `presenceHeartbeat` | **insert** [ticket_presence](/code/tables/ticket_presence.md) (`ticket_id`, `analyst_id`, `last_seen`, `is_composing`) |  |

## Calls

[presenceSchemaReady()](/code/functions/presenceSchemaReady.md)

## Called from

- [api/tickets/ticket_presence.php](/code/files/api/tickets/ticket_presence.php.md)

