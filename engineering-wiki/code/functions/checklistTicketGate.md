# checklistTicketGate()

🔒 Refuse unless this analyst can reach the ticket. Found while building the task twin of this endpoint (discussion #138): NOTHING here checked company - or even Tickets module access - so any signed-in analyst could read, attach to, tick and remove checklists on another company's ticket by number. The step and checklist actions are addressed by a CHILD id, so they resolve it to its ticket first and gate on that; a child id with no gate of its own is the classic way scoped data leaks. "Not found", never "not yours".

```php
function checklistTicketGate(PDO $conn, int $ticketId): void
```

Defined in [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md), line 38.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Calls

[analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md)

## Called from

- [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md)

