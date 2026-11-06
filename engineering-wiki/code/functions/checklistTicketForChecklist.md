# checklistTicketForChecklist()

Returns the ticket a checklist belongs to, or 0 when it does not exist - so the caller can run the ticket access gate.

```php
function checklistTicketForChecklist(PDO $conn, int $checklistId): int
```

Defined in [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md), line 62.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$checklistId` | `int` |  | the ticket_checklists id |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 64 | `checklistTicketForChecklist` | read [ticket_checklists](/code/tables/ticket_checklists.md) |  |

## Called from

- [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md)

