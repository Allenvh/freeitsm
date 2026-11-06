# portalKnowledgeScope()

The WHERE fragment and bound params for "articles this requester may read".

Returns SQL beginning with a condition (no leading AND), so a caller writes:

    [`$where`, `$params`] = portalKnowledgeScope(`$conn`, `$tenantId`, 'a');
    `$sql` = "SELECT ... FROM knowledge_articles a WHERE `$where` ORDER BY ...";

Callers add their own extra conditions AFTER, appending their params in the
same order.

```php
function portalKnowledgeScope(PDO $conn, ?int $tenantId, string $alias = 'a'): array
```

Defined in [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tenantId` | `?int` |  | the requester's company (null = shared only) |
| `$alias` | `string` | `'a'` | the table alias used in the caller's query |

**Returns:** array{0:string,1:array}

## Calls

[knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

## Called from

- [api/self-service/get_knowledge_article.php](/code/files/api/self-service/get_knowledge_article.php.md)
- [api/self-service/get_knowledge_articles.php](/code/files/api/self-service/get_knowledge_articles.php.md)
- [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md) - in [portalCanReadArticle()](/code/functions/portalCanReadArticle.md)

