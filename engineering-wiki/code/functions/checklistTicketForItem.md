# checklistTicketForItem()

Returns the ticket a checklist item belongs to, or 0 when the item does not exist - so the caller can run the ticket access gate.

```php
function checklistTicketForItem(PDO $conn, int $itemId): int
```

Defined in [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md), line 50.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$itemId` | `int` |  | the ticket_checklist_items id |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 52 | `checklistTicketForItem` | read [ticket_checklist_items](/code/tables/ticket_checklist_items.md), read [ticket_checklists](/code/tables/ticket_checklists.md) |  |

## Called from

- [api/tickets/ticket_checklists.php](/code/files/api/tickets/ticket_checklists.php.md)

