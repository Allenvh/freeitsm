# presenceOthers()

Everyone ELSE currently on this ticket, most recently seen first.

Joins `analysts` for the display name and filters `is_active`, so somebody
deactivated mid-session stops being announced. Returns [] on any problem —
an empty strip is a fine answer, a broken reading pane is not.

```php
function presenceOthers(PDO $conn, int $ticketId, int $analystId): array
```

Defined in [includes/ticket_presence.php](/code/files/includes/ticket_presence.php.md), line 99.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 103 | `presenceOthers` | read [ticket_presence](/code/tables/ticket_presence.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[presenceInitials()](/code/functions/presenceInitials.md), [presenceSchemaReady()](/code/functions/presenceSchemaReady.md)

## Called from

- [api/tickets/ticket_presence.php](/code/files/api/tickets/ticket_presence.php.md)

