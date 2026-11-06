# searchIndexArticle()

Index (or reindex) one knowledge article.

⚠️ WHY THIS IS CALLED DIRECTLY AND TICKETS ARE NOT
Tickets use the dispatch seam because three separate paths create them and
none of them shares code. Articles are the opposite: `KnowledgeService` is the
only thing that writes `knowledge_articles`, so calling from there is both
complete and obvious.

The events would also be the wrong hook. A newly created DRAFT fires nothing
at all — `knowledge.published` is deliberately withheld so a workflow does not
announce a page nobody can open yet — but a draft still needs indexing,
because the palette deliberately shows analysts their own work in progress.
"The text changed" and "tell people about it" are different questions here.

An archived or deleted article has its row removed rather than hidden: the
command palette excludes archived articles, so leaving them searchable would
disagree with the rest of the product.

```php
function searchIndexArticle(PDO $conn, int $articleId, int $maxBody = SEARCH_INDEX_MAX_BODY): bool
```

Defined in [includes/search/indexer.php](/code/files/includes/search/indexer.php.md), line 335.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$articleId` | `int` |  | the knowledge article to index; 0 or less does nothing |
| `$maxBody` | `int` | `SEARCH_INDEX_MAX_BODY` | the most characters of the article body to index |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 346 | `searchIndexArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[searchCorpusArticleScope()](/code/functions/searchCorpusArticleScope.md), [searchCorpusDelete()](/code/functions/searchCorpusDelete.md), [searchCorpusPlainText()](/code/functions/searchCorpusPlainText.md), [searchCorpusUpsert()](/code/functions/searchCorpusUpsert.md)

## Called from

- [includes/search/backfill.php](/code/files/includes/search/backfill.php.md) - in [searchBackfillRun()](/code/functions/searchBackfillRun.md)
- [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) - in `KnowledgeService::reindexForSearch`

