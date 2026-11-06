# ticketCategoryDepthUnder()

The depth a NEW child of `$parentId` would sit at, 1 for a root.

Used by the save endpoint to refuse a fourth level before it is written,
rather than discovering it when a report cannot render.

```php
function ticketCategoryDepthUnder(PDO $conn, ?int $parentId): int
```

Defined in [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md), line 233.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$parentId` | `?int` |  | the would-be parent category; null or 0 for a root |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 239 | `ticketCategoryDepthUnder` | read [ticket_categories](/code/tables/ticket_categories.md) |  |

## Called from

- [api/tickets/save_ticket_category.php](/code/files/api/tickets/save_ticket_category.php.md)

