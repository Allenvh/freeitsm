# ticketCategorySortTree()

Order a resolved set depth-first: each root, then its descendants, by display_order then name at every level.

```php
function ticketCategorySortTree(array $rows): array
```

Defined in [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md), line 164.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$rows` | `array` |  | ticket_categories rows keyed by id (id, parent_id, name, display_order) |

**Returns:** array<int,array>

## Called from

- [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md) - in [ticketCategoriesResolved()](/code/functions/ticketCategoriesResolved.md)

