# splitMessagesByIds()

The messages a split would move when the analyst has ticked them INDIVIDUALLY, rather than choosing an anchor and "everything newer". Returned oldest-first so the new ticket, the marker timestamp and the subject default all behave exactly as they do on the anchor path.

Every id must be a real, movable message on this ticket. A marker is refused (it is a
placeholder, not content) and an id from another ticket — or one that has since moved
away — is refused too: a split that quietly moved fewer messages than the analyst
ticked would be its own small betrayal. So it throws rather than silently dropping.

```php
function splitMessagesByIds(PDO $conn, int $ticketId, array $emailIds): array
```

Defined in [includes/ticket_split.php](/code/files/includes/ticket_split.php.md), line 125.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |
| `$emailIds` | `array` |  | the emails.id values the analyst ticked; split markers and ids not on this ticket throw |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 136 | `splitMessagesByIds` | read [emails](/code/tables/emails.md) |  |

## Calls

[splitMarkerEmailIds()](/code/functions/splitMarkerEmailIds.md)

## Called from

- [includes/ticket_split.php](/code/files/includes/ticket_split.php.md) - in [splitTicket()](/code/functions/splitTicket.md)

