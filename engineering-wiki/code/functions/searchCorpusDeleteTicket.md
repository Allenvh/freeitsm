# searchCorpusDeleteTicket()

Remove every row for a ticket. The FK cascades on ticket DELETE; this is for soft deletes.

```php
function searchCorpusDeleteTicket(PDO $conn, int $ticketId): void
```

Defined in [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), line 143.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ticketId` | `int` |  | The id of the ticket. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 144 | `searchCorpusDeleteTicket` | **delete** [search_documents](/code/tables/search_documents.md) |  |

## Called from

- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexTicket()](/code/functions/searchIndexTicket.md), [searchIndexHandleEvent()](/code/functions/searchIndexHandleEvent.md)

