# searchUnindexDocument()

Drop a document from the index — used when its last link goes.

```php
function searchUnindexDocument(PDO $conn, int $documentId): void
```

Defined in [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), line 156.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$documentId` | `int` |  | the documents id |

**Returns:** `void`

## Calls

[searchCorpusDelete()](/code/functions/searchCorpusDelete.md)

## Called from

- [api/documents/unlink.php](/code/files/api/documents/unlink.php.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentsCollectOrphans()](/code/functions/documentsCollectOrphans.md)

