# knowledgeVisibilitySql()

The SQL clause restricting a knowledge_articles query to what `$viewer` may read.

Returns [' AND ...', `$params`] ready to concatenate into a WHERE, or ['', []]
when nothing needs restricting. The leading ' AND ' is included, so callers
append it to an existing predicate exactly as they do today with
`knowledgeTenantFilter()` and Audience::sqlFilter().

`$alias` is the table alias ('' for an unaliased query).

`$opts`:
  'lifecycle' => 'live' (default) | 'published' | 'unarchived' | 'archived' | 'any'

      live        published AND not archived — every customer-facing reader
      published   published, ANY archive state (the AI chat's include-archived)
      unarchived  not archived, ANY publish state (the analyst list, with drafts)
      archived    archived only (the recycle bin)
      any         no lifecycle filter at all (the indexer, and total counts)

      Lifecycle is NOT a permission and is therefore separable. Three real
      callers prove it has to be: the recycle bin wants archived rows, the
      search indexer wants everything, and the analyst article list
      deliberately shows UNPUBLISHED drafts — the Knowledge assistant saves
      AI-written work as a draft, and a draft nobody can find is a draft
      nobody will ever publish.

      Had lifecycle been welded into the permission clause, each of those
      would have had to opt OUT of the security filter to get the rows it
      needs. That is precisely the shape of mistake this file exists to
      prevent, so they are separate knobs.

```php
function knowledgeVisibilitySql(
    PDO $conn,
    KnowledgeViewer $viewer,
    string $alias = 'a',
    array $opts = []
): array
```

Defined in [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), line 346.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$viewer` | `KnowledgeViewer` |  | who is reading |
| `$alias` | `string` | `'a'` | the knowledge_articles alias in the calling query; '' when it has none |
| `$opts` | `array` | `[]` | lifecycle ('live', 'published', 'unarchived', 'archived', 'any'); no_admin_floor (true skips the floor) |

**Returns:** `array`

## Calls

[knowledgeAclSql()](/code/functions/knowledgeAclSql.md), [tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

## Called from

- [api/files/shortcut.php](/code/files/api/files/shortcut.php.md)
- [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md)
- [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) - in [handleList()](/code/functions/handleList.md)
- [api/knowledge/get_articles_for_embedding.php](/code/files/api/knowledge/get_articles_for_embedding.php.md)
- [api/knowledge/get_embedding_stats.php](/code/files/api/knowledge/get_embedding_stats.php.md)
- [api/knowledge/get_review_list.php](/code/files/api/knowledge/get_review_list.php.md)
- [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md) - in [handleList()](/code/functions/handleList.md)
- [api/knowledge/knowledge_article.php](/code/files/api/knowledge/knowledge_article.php.md)
- [api/knowledge/knowledge_articles.php](/code/files/api/knowledge/knowledge_articles.php.md)
- [api/knowledge/knowledge_tags.php](/code/files/api/knowledge/knowledge_tags.php.md)
- [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md) - in [handlePreview()](/code/functions/handlePreview.md)
- [api/system/global_search.php](/code/files/api/system/global_search.php.md)
- [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) - in [apiKnowledgeArticlesList()](/code/functions/apiKnowledgeArticlesList.md)
- [includes/documents.php](/code/files/includes/documents.php.md) - in [documentEntityRegistry()](/code/functions/documentEntityRegistry.md)
- [includes/domains/links.php](/code/files/includes/domains/links.php.md) - in [domainLinkSearch()](/code/functions/domainLinkSearch.md)
- [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) - in [filesArticleTarget()](/code/functions/filesArticleTarget.md)
- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)
- [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md) - in [kbRetrieveArticles()](/code/functions/kbRetrieveArticles.md)
- [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md) - in [portalKnowledgeScope()](/code/functions/portalKnowledgeScope.md)
- [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) - in [knowledgeCanRead()](/code/functions/knowledgeCanRead.md)
- [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) - in [mcpToolSearchKnowledge()](/code/functions/mcpToolSearchKnowledge.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectLinkSearch()](/code/functions/projectLinkSearch.md)
- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/record_preview.php](/code/files/includes/record_preview.php.md) - in [recordPreviewKnowledgeArticle()](/code/functions/recordPreviewKnowledgeArticle.md)
- [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) - in [warbotToolSearchKnowledge()](/code/functions/warbotToolSearchKnowledge.md)
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

