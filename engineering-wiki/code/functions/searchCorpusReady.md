# searchCorpusReady()

Is the corpus available? False on an install that has not run Database Verification.

```php
function searchCorpusReady(PDO $conn): bool
```

Defined in [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), line 148.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 152 | `searchCorpusReady` | read [search_documents](/code/tables/search_documents.md) |  |

## Called from

- [api/system/search_status.php](/code/files/api/system/search_status.php.md)
- [api/tickets/check_mailbox_email.php](/code/files/api/tickets/check_mailbox_email.php.md) - in [saveEmailToDatabase()](/code/functions/saveEmailToDatabase.md)
- [includes/search/backfill.php](/code/files/includes/search/backfill.php.md) - in [searchBackfillRun()](/code/functions/searchBackfillRun.md)
- [includes/search/documents_index.php](/code/files/includes/search/documents_index.php.md) - in [searchIndexDocument()](/code/functions/searchIndexDocument.md)
- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexHandleEvent()](/code/functions/searchIndexHandleEvent.md)
- [includes/search/search.php](/code/files/includes/search/search.php.md) - in [searchCorpusQuery()](/code/functions/searchCorpusQuery.md)
- [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) - in `KnowledgeService::reindexForSearch`

