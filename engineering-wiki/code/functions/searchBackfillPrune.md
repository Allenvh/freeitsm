# searchBackfillPrune()

Remove every corpus row whose ticket has since been deleted or trashed. The foreign key handles a hard DELETE; this covers the soft-delete case.

```php
function searchBackfillPrune(PDO $conn): int
```

Defined in [includes/search/backfill.php](/code/files/includes/search/backfill.php.md), line 159.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 160 | `searchBackfillPrune` | **delete** [search_documents](/code/tables/search_documents.md), read [tickets](/code/tables/tickets.md) |  |

## Called from

- [api/system/search_rebuild.php](/code/files/api/system/search_rebuild.php.md)

