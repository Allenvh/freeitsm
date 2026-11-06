# searchCorpusDelete()

Remove one source's row. Safe to call for something never indexed.

```php
function searchCorpusDelete(PDO $conn, string $sourceType, int $sourceId): void
```

Defined in [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), line 137.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$sourceType` | `string` |  | the source type, a SEARCH_SOURCE_* value such as 'email' or 'document' |
| `$sourceId` | `int` |  | the source row's id in its own table |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 138 | `searchCorpusDelete` | **delete** [search_documents](/code/tables/search_documents.md) |  |

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [searchIndexDocument()](/code/functions/searchIndexDocument.md), [searchUnindexDocument()](/code/functions/searchUnindexDocument.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexArticle()](/code/functions/searchIndexArticle.md)

