# searchBackfillRun()

Fills the search corpus from existing tickets, emails, notes, attachments and Knowledge articles, in resumable batches.

```php
function searchBackfillRun(PDO $conn, array $opts = [], ?callable $progress = null): array
```

Defined in [includes/search/backfill.php](/code/files/includes/search/backfill.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` | `[]` | batch (50-2000), max_body_chars, since_ticket_id (resume point), limit (0 = all), articles (false skips) |
| `$progress` | `?callable` | `null` | fn(string `$stage`, int `$done`, int `$total`): void |

**Returns:** array{tickets:int,emails:int,notes:int,articles:int,skipped:int,seconds:float}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 66 | `searchBackfillRun` | read [tickets](/code/tables/tickets.md) |  |
| 70 | `searchBackfillRun` | read [tickets](/code/tables/tickets.md) |  |
| 105 | `searchBackfillRun` | read [tickets](/code/tables/tickets.md) |  |
| 126 | `searchBackfillRun` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchIndexArticle()](/code/functions/searchIndexArticle.md), [searchIndexTicket()](/code/functions/searchIndexTicket.md)

## Called from

- [api/system/search_rebuild.php](/code/files/api/system/search_rebuild.php.md)

