# ticketViewsForRequester()

Portal viewers of a ticket OTHER than its requester, newest first - what the requester is shown under "Who has seen this ticket". Analysts are deliberately absent (see the header).

```php
function ticketViewsForRequester(PDO $conn, int $ticketId, int $requesterUserId): array
```

Defined in [includes/ticket_views.php](/code/files/includes/ticket_views.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$requesterUserId` | `int` |  | the requester's users.id, left out of the list |

**Returns:** array<int, array{name:string, first:string, last:string, count:int}>

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 136 | `ticketViewsForRequester` | read [ticket_views](/code/tables/ticket_views.md), read [users](/code/tables/users.md) |  |

## Calls

[ticketViewsReady()](/code/functions/ticketViewsReady.md)

## Called from

- [api/self-service/get_ticket_detail.php](/code/files/api/self-service/get_ticket_detail.php.md)

