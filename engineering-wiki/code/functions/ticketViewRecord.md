# ticketViewRecord()

Record that someone opened a ticket. Never throws: a view that fails to be recorded must not stop the ticket from opening.

```php
function ticketViewRecord(PDO $conn, int $ticketId, string $viewerType, int $viewerId): void
```

Defined in [includes/ticket_views.php](/code/files/includes/ticket_views.php.md), line 54.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$viewerType` | `string` |  | 'analyst' or 'user' (a self-service portal user) |
| `$viewerId` | `int` |  | analysts.id or users.id, according to `$viewerType`; 0 or less records nothing |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 64 | `ticketViewRecord` | **insert** [ticket_views](/code/tables/ticket_views.md) (`ticket_id`, `viewer_type`, `viewer_id`, `view_date`, `first_viewed_datetime`, `last_viewed_datetime`, `view_count`) |  |

## Calls

[ticketViewsReady()](/code/functions/ticketViewsReady.md)

## Called from

- [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md)
- [api/tickets/get_email_detail.php](/code/files/api/tickets/get_email_detail.php.md)

