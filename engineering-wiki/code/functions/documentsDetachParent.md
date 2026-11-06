# documentsDetachParent()

Detach everything from a record that is being deleted.

Call this from a module's delete path when you have one. It is the tidy answer:
the links go at the moment the parent does.

```php
function documentsDetachParent(PDO $conn, string $parentType, int $parentId): int
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 622.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$parentType` | `string` |  | the deleted record's entity type key from `documentEntityRegistry()`; unknown does nothing |
| `$parentId` | `int` |  | the deleted record's id |

**Returns:** int links removed

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 626 | `documentsDetachParent` | **delete** [document_links](/code/tables/document_links.md) |  |

## Calls

[documentEntityDef()](/code/functions/documentEntityDef.md)

## Called from

- [api/tickets/empty_trash.php](/code/files/api/tickets/empty_trash.php.md)
- [api/tickets/permanently_delete_ticket.php](/code/files/api/tickets/permanently_delete_ticket.php.md)
- [includes/leases.php](/code/files/includes/leases.php.md) - in [leaseDelete()](/code/functions/leaseDelete.md)
- [includes/services/domains.php](/code/files/includes/services/domains.php.md) - in `DomainsService::deleteDomain`

