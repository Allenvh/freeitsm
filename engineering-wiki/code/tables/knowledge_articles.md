# knowledge_articles

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `title` | `VARCHAR(255) NOT NULL` |  |
| `body` | `LONGTEXT NULL` |  |
| `author_id` | `INT NOT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `modified_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `is_published` | `TINYINT(1) NULL DEFAULT 1` |  |
| `view_count` | `INT NULL DEFAULT 0` |  |
| `next_review_date` | `DATE NULL` |  |
| `owner_id` | `INT NULL` |  |
| `embedding` | `LONGTEXT NULL` |  |
| `embedding_updated` | `DATETIME NULL` |  |
| `is_archived` | `TINYINT(1) NULL DEFAULT 0` |  |
| `archived_datetime` | `DATETIME NULL` |  |
| `archived_by_id` | `INT NULL` |  |
| `version` | `INT NOT NULL DEFAULT 1` |  |
| `tenant_id` | `INT NULL` | Which company OWNS the article. ⚠️ NULL = SHARED WITH EVERY COMPANY here, NOT "belongs to Default" as it does for tickets/assets — Knowledge has its own filter helper for exactly this reason (see includes/tenancy.php). NULL is also the zero-migration default: existing articles stay shared, which is precisely today's behaviour. |
| `audience` | `VARCHAR(20) NOT NULL DEFAULT 'internal'` | WHO may read it: 'internal' \| 'customer' \| 'public'. Defaults to 'internal' so running Database Verify can NEVER start disclosing existing articles to anonymous web chat visitors — authors opt in per article. |
| `folder_id` | `INT NULL` | ── Folders and per-document permissions ──────────────────────────── Which folder the article lives in. NULL = the root, which is where every existing article lands on upgrade: zero migration, and the resulting state (root, inheriting, unrestricted) is indistinguishable from how the module behaved before folders existed. |
| `is_restricted` | `TINYINT(1) NOT NULL DEFAULT 0` | 0 = Open: readable unless a DENY names you. 1 = Restricted: readable only if a GRANT names you. The polarity lives on the OBJECT, never on the access rows, so an allow and a deny cannot coexist and there is no precedence rule. |
| `inherit_permissions` | `TINYINT(1) NOT NULL DEFAULT 1` | 1 = take permissions from the parent folder and ignore my own rows. Default 1 so an upgraded install inherits from a root that restricts nothing — i.e. nothing changes. |
| `is_demo` | `TINYINT(1) NOT NULL DEFAULT 0` | set by the demo data importer (#1297) |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md):331 | `handleMoveArticle` | update | `folder_id`, `modified_datetime` |  |
| [api/knowledge/generate_embedding.php](/code/files/api/knowledge/generate_embedding.php.md):129 |  | update | `embedding`, `embedding_updated` |  |
| [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md):139 | `purgeExpired` | delete |  |  |
| [api/knowledge/knowledge_article.php](/code/files/api/knowledge/knowledge_article.php.md):79 |  | update | `view_count` |  |
| [api/self-service/get_knowledge_article.php](/code/files/api/self-service/get_knowledge_article.php.md):84 |  | update | `view_count` |  |
| [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md):166 | `apiKnowledgePurgeExpired` | delete |  |  |
| [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md):299 | `apiKnowledgeArticlesGet` | update | `view_count` |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):237 | `KnowledgeService::saveArticle` | update |  |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):293 | `KnowledgeService::saveArticle` | insert | `title`, `body`, `author_id`, `owner_id`, `next_review_date`, `created_datetime`, `modified_datetime`, `is_published`, `view_count`, `tenant_id` |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):369 | `KnowledgeService::archiveArticle` | update | `is_archived`, `archived_datetime`, `archived_by_id` |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):395 | `KnowledgeService::restoreArticle` | update | `is_archived`, `archived_datetime`, `archived_by_id` |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):423 | `KnowledgeService::purgeArticle` | delete |  |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md):526 | `KnowledgeService::updateEmbedding` | update | `embedding`, `embedding_updated` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/files/shortcut.php](/code/files/api/files/shortcut.php.md) | L38, L59 |  |
| [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md) | L154, L172, L210, L220, L225, L231 |  |
| [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md) | handleList L87, handleList L94, handleDelete L277, handleAddShortcut L373, handleExceptions L450 |  |
| [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md) | L60 |  |
| [api/knowledge/generate_embedding.php](/code/files/api/knowledge/generate_embedding.php.md) | L59 |  |
| [api/knowledge/get_articles_for_embedding.php](/code/files/api/knowledge/get_articles_for_embedding.php.md) | L33 |  |
| [api/knowledge/get_embedding_stats.php](/code/files/api/knowledge/get_embedding_stats.php.md) | L34, L40 |  |
| [api/knowledge/get_review_list.php](/code/files/api/knowledge/get_review_list.php.md) | L34, L88 |  |
| [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md) | handleList L68, purgeExpired L128 |  |
| [api/knowledge/knowledge_article.php](/code/files/api/knowledge/knowledge_article.php.md) | L35 |  |
| [api/knowledge/knowledge_articles.php](/code/files/api/knowledge/knowledge_articles.php.md) | L39 |  |
| [api/knowledge/knowledge_tags.php](/code/files/api/knowledge/knowledge_tags.php.md) | L39 |  |
| [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md) | handlePreview L128 |  |
| [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md) | resolveInherited L165 |  |
| [api/lms/ai_author.php](/code/files/api/lms/ai_author.php.md) | L92 |  |
| [api/lms/knowledge_articles.php](/code/files/api/lms/knowledge_articles.php.md) | L27 |  |
| [api/self-service/get_knowledge_article.php](/code/files/api/self-service/get_knowledge_article.php.md) | L46 |  |
| [api/self-service/get_knowledge_articles.php](/code/files/api/self-service/get_knowledge_articles.php.md) | L72 |  |
| [api/system/global_search.php](/code/files/api/system/global_search.php.md) | L276, L604 |  |
| [api/system/search_status.php](/code/files/api/system/search_status.php.md) | L75 |  |
| [api/v1/lib/auth.php](/code/files/api/v1/lib/auth.php.md) | apiKeyCanAccessArticle L352 |  |
| [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md) | apiKnowledgeSelect L51, apiKnowledgePurgeExpired L158, apiKnowledgeArticlesList L272, apiKnowledgeTagsList L409 |  |
| [includes/domains/links.php](/code/files/includes/domains/links.php.md) | domainLinkDescribe L181, domainLinkSearch L290 |  |
| [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md) | filesArticleTarget L223 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapAnalyse L233 |  |
| [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md) | helpCentreArticleIsCustomerFacing L40 |  |
| [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md) | kbRetrieveArticles L141, kbRetrieveArticles L149, kbRetrieveArticles L167 |  |
| [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md) | portalCanReadArticle L104 |  |
| [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md) | knowledgeCanRead L442, knowledgeAclSql L599, knowledgeAclHasAnyRows L849 |  |
| [includes/lms/knowledge_source.php](/code/files/includes/lms/knowledge_source.php.md) | lmsCanUseArticle L58 |  |
| [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md) | mcpToolSearchKnowledge L363 |  |
| [includes/projects/links.php](/code/files/includes/projects/links.php.md) | projectLinkDescribe L241, projectLinkSearch L372 |  |
| [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) | recentTrailLabelsForType L541 |  |
| [includes/record_preview.php](/code/files/includes/record_preview.php.md) | recordPreviewKnowledgeArticle L444 |  |
| [includes/search/backfill.php](/code/files/includes/search/backfill.php.md) | searchBackfillRun L126 |  |
| [includes/search/indexer.php](/code/files/includes/search/indexer.php.md) | searchIndexArticle L346 |  |
| [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md) | KnowledgeService::assertCanAccessArticle L144, KnowledgeService::saveArticle L231, KnowledgeService::loadArticleRow L447 |  |
| [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) | ProjectToolsService::raid L838 |  |
| [includes/tenancy.php](/code/files/includes/tenancy.php.md) | analystCanAccessArticle L1012 |  |
| [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md) | warbotToolSearchKnowledge L978 |  |
| [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) | getWatchtowerData L688, getWatchtowerData L699 |  |

