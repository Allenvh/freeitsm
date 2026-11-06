# searchIndexDocument()

Put one document into the search corpus.

The row carries title + description + whatever text was extracted. It carries
NO permission facts beyond the tenant, deliberately: who may see a document
depends on what it is attached to, which changes after indexing. That question
is answered at query time — see `documentSearchVisibilityClause()`.

```php
function searchIndexDocument(PDO $conn, int $documentId): void
```

Defined in [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md), line 107.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$documentId` | `int` |  | the documents id |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 112 | `searchIndexDocument` | read [documents](/code/tables/documents.md), read [document_text](/code/tables/document_text.md) |  |

## Calls

[searchCorpusDelete()](/code/functions/searchCorpusDelete.md), [searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchCorpusTicketScope()](/code/functions/searchCorpusTicketScope.md), [searchCorpusUpsert()](/code/functions/searchCorpusUpsert.md)

## Called from

- [api/documents/attach.php](/code/files/api/documents/attach.php.md)
- [api/documents/save.php](/code/files/api/documents/save.php.md)
- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [documentTextDrain()](/code/functions/documentTextDrain.md)

