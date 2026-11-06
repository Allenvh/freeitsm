# searchCorpusArticleScope()

Turn a knowledge-article tenant_id into the pair the corpus stores. NULL is the OPPOSITE of the ticket case — see [[project_knowledge_visibility]].

```php
function searchCorpusArticleScope(?int $tenantId): array
```

Defined in [includes/search/corpus.php](/code/files/includes/search/corpus.php.md), line 61.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$tenantId` | `?int` |  | A company (tenant) id; null means the Default company. *([standard](/handbook/standard-parameters.md))* |

**Returns:** array{0:?int,1:string}

## Called from

- [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) - in [searchIndexArticle()](/code/functions/searchIndexArticle.md)

