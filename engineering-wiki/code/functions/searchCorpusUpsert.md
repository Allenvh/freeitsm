# searchCorpusUpsert()

Index one source row, or update it if already indexed.

```php
function searchCorpusUpsert(PDO $conn, array $doc): void
```

Defined in [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), line 97.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$doc` | `array` |  | source_type, source_id, and optionally ticket_id, tenant_id, tenant_scope, is_internal, title, body, source_datetime. |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 104 | `searchCorpusUpsert` | **insert** [search_documents](/code/tables/search_documents.md) (`source_type`, `source_id`, `ticket_id`, `tenant_id`, `tenant_scope`, `is_internal`, `title`, `body`, `source_datetime`, `indexed_datetime`) |  |

## Called from

- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [searchIndexDocument()](/code/functions/searchIndexDocument.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicket()](/code/functions/searchIndexTicket.md), [searchIndexTicketAttachments()](/code/functions/searchIndexTicketAttachments.md), [searchIndexArticle()](/code/functions/searchIndexArticle.md)

