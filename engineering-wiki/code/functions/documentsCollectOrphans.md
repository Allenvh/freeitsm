# documentsCollectOrphans()

Remove links whose parent no longer exists, then collect what that orphans.

⚠️ WHY A SWEEP AND NOT ONLY A HOOK. document_links.parent_id is POLYMORPHIC — it
points at whichever table parent_type names — so no foreign key can protect it.
Delete a contract and its links survive; nothing in the database can object.
The document is invisible from that moment (every permission check verifies the
parent still exists, which is why nothing leaks) but it is never collected: the
file sits on disk for ever and the row never orphans.

A hook on every module's delete would be the tidy answer, and `documentsDetachParent()`
is there for the ones that have one. It cannot be the ONLY answer: twelve delete
paths, plus bulk deletes, plus anything that ever removes a row by SQL, is twelve
chances to forget — and forgetting is silent. So this is the net, and it is
correct on its own.

Bounded so it can run opportunistically without becoming the cost of a page load.

```php
function documentsCollectOrphans(PDO $conn, int $limit = 200): array
```

Defined in [includes/documents.php](/code/files/includes/documents.php.md), line 657.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$limit` | `int` | `200` | most rows to delete per entity type, and most documents to collect, in one run (at least 1) |

**Returns:** array{links_removed:int,documents_orphaned:int,files_removed:int,errors:string[]}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 670 | `documentsCollectOrphans` | **delete** [document_links](/code/tables/document_links.md) |  |
| 690 | `documentsCollectOrphans` | read [documents](/code/tables/documents.md), read [document_links](/code/tables/document_links.md) |  |
| 700 | `documentsCollectOrphans` | **update** [documents](/code/tables/documents.md) (`deleted_datetime`) |  |
| 711 | `documentsCollectOrphans` | read [documents](/code/tables/documents.md) |  |

## Calls

[documentEntityRegistry()](/code/functions/documentEntityRegistry.md), [documentStoragePath()](/code/functions/documentStoragePath.md), [searchUnindexDocument()](/code/functions/searchUnindexDocument.md)

## Called from

- [api/documents/list.php](/code/files/api/documents/list.php.md)

