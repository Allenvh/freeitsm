# ticketCategoriesResolved()

Every category this company can see, each with its derived path and root.

```php
function ticketCategoriesResolved(PDO $conn, ?int $tenantId, array $opts = []): array
```

Defined in [includes/ticket_categories.php](/code/files/includes/ticket_categories.php.md), line 58.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | the company whose lists apply, or null for the install |
| `$opts` | `array` | `[]` | activeOnly  — drop retired categories (default false) portalOnly  — only those a requester may see (default false) typeId      — only categories offered for this ticket type (roots with no type, plus that type's roots, plus everything beneath either) |

**Returns:** array<int,array> keyed by id, each row carrying: depth, path (array of names), path_label, root_id, effective_type_id, has_children

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 74 | `ticketCategoriesResolved` | read [ticket_categories](/code/tables/ticket_categories.md) |  |

## Calls

[getTenantConfigRows()](/code/functions/getTenantConfigRows.md), [ticketCategorySortTree()](/code/functions/ticketCategorySortTree.md)

## Called from

- [api/self-service/create_ticket.php](/code/files/api/self-service/create_ticket.php.md)
- [api/self-service/get_ticket_categories.php](/code/files/api/self-service/get_ticket_categories.php.md)
- [api/tickets/get_ticket_classification.php](/code/files/api/tickets/get_ticket_classification.php.md)

