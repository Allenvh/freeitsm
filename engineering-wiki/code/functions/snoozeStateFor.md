# snoozeStateFor()

Read the sleep state of one ticket for the reading pane: null when it is awake, otherwise the wake time (UTC), who put it there and why.

```php
function snoozeStateFor(PDO $conn, int $ticketId): ?array
```

Defined in [includes/ticket_snooze.php](/code/files/includes/ticket_snooze.php.md), line 287.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `?array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 291 | `snoozeStateFor` | read [tickets](/code/tables/tickets.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[snoozeSchemaReady()](/code/functions/snoozeSchemaReady.md)

## Called from

- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)

