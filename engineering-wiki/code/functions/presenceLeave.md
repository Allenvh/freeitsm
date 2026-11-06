# presenceLeave()

Drop this analyst's row — the optimisation that makes leaving instant.

```php
function presenceLeave(PDO $conn, int $ticketId, int $analystId): void
```

Defined in [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md), line 131.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 135 | `presenceLeave` | **delete** [ticket_presence](/code/tables/ticket_presence.md) |  |
| 139 | `presenceLeave` | **delete** [ticket_presence](/code/tables/ticket_presence.md) |  |

## Calls

[presenceSchemaReady()](/code/functions/presenceSchemaReady.md)

## Called from

- [api/tickets/ticket_presence.php](/code/files/api/tickets/ticket_presence.php.md)

