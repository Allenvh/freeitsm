# searchCorpusQuery()

Search the corpus.

Two bounded queries rather than one clever one: rank the TICKETS, then fetch
the matching documents for just that page of tickets. Doing it the other way
round — fetch documents, collapse afterwards — reintroduces a top-N
distortion, because the top 200 documents may collapse to a handful of
tickets.

```php
function searchCorpusQuery(PDO $conn, string $rawQuery, array $scope, array $opts = []): array
```

Defined in [includes/search/search.php](/code/files/includes/search/search.php.md), line 255.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$rawQuery` | `string` |  | the search text as the user typed it - untrusted |
| `$scope` | `array` |  | the scope structure built by `searchScopeForAnalyst()` |
| `$opts` | `array` | `[]` | limit, offset, title_only, snippet_chars |

**Returns:** array{total:int,results:array,query:array,ok:bool,reason:?string}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 279 | `searchCorpusQuery` | read [tickets](/code/tables/tickets.md) |  |
| 286 | `searchCorpusQuery` | read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 294 | `searchCorpusQuery` | read [search_documents](/code/tables/search_documents.md) |  |
| 301 | `searchCorpusQuery` | read [search_documents](/code/tables/search_documents.md) |  |
| 316 | `searchCorpusQuery` | read [search_documents](/code/tables/search_documents.md) |  |

## Calls

[searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchMinTokenSize()](/code/functions/searchMinTokenSize.md), [searchParseQuery()](/code/functions/searchParseQuery.md), [searchScopeToSql()](/code/functions/searchScopeToSql.md), [searchSnippet()](/code/functions/searchSnippet.md)

## Called from

- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/tickets/search_content.php](/code/files/api/tickets/search_content.php.md)

