# includes/services/knowledge.php

> PHP - 569 lines

KnowledgeService — the shared write rules for the Knowledge Base: article create/update (tags + save-as-version + best-effort embedding regeneration) and the recycle-bin trio archive / restore / purge.

Shared by the UI endpoints (api/knowledge/*.php) and the REST API
(api/v1/resources/knowledge.php). Each caller passes an ActorContext +
canonical input; this layer validates + writes and returns the affected id(s)
or throws ServiceError. It never emits HTTP.

Canonical behaviour = the API resource's: title required + ≤255 chars, owner
must be a real active analyst, next_review_date validated, updating an
archived article is refused (409), and the recycle-bin guards (archive an
archived article / restore a live one / purge a live one → 409). Articles are
created *published* unless the caller passes is_published => false; the only
caller that does is the Knowledge assistant, whose AI-written drafts must
reach a human before a reader. Bodies are TinyMCE HTML stored verbatim (no
sanitisation exists anywhere).

⚙️ Side effect: after a save, the OpenAI search embedding is regenerated so
AI chat / vector search keep working. It is deliberately BEST-EFFORT — it runs
*after* the article is committed, is wrapped in try/catch, and any failure
(no key configured, an invalid key, a missing key file, an OpenAI error/
timeout) is swallowed. The article is always saved regardless; the embedding
just doesn't get updated. This service requires encryption.php so the step
works on BOTH the UI and the API (the API path historically lacked
`decryptValue()` and silently skipped embeddings — #727 fixed that so
API-written articles are searchable too). No key configured → still a clean
no-op.

Canonical input keys: title, body_html, tags[], owner_id, next_review_date,
save_as_version. The UI adapter maps its `body` to `body_html`.

## Classes

- [KnowledgeService](/code/classes/KnowledgeService.md) (class, line 43)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 95 | `KnowledgeService::resolveFolderId` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 144 | `KnowledgeService::assertCanAccessArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 231 | `KnowledgeService::saveArticle` | **insert** [knowledge_article_versions](/code/tables/knowledge_article_versions.md) (`article_id`, `version`, `title`, `body`, `saved_by_id`, `saved_datetime`), read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 237 | `KnowledgeService::saveArticle` | **update** [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 293 | `KnowledgeService::saveArticle` | **insert** [knowledge_articles](/code/tables/knowledge_articles.md) (`title`, `body`, `author_id`, `owner_id`, `next_review_date`, `created_datetime`, `modified_datetime`, `is_published`, `view_count`, `tenant_id`, `audience`, `folder_id`) |  |
| 369 | `KnowledgeService::archiveArticle` | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`is_archived`, `archived_datetime`, `archived_by_id`) |  |
| 395 | `KnowledgeService::restoreArticle` | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`is_archived`, `archived_datetime`, `archived_by_id`) |  |
| 421 | `KnowledgeService::purgeArticle` | **delete** [knowledge_article_versions](/code/tables/knowledge_article_versions.md) |  |
| 422 | `KnowledgeService::purgeArticle` | **delete** [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 423 | `KnowledgeService::purgeArticle` | **delete** [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 424 | `KnowledgeService::purgeArticle` | **delete** [knowledge_tags](/code/tables/knowledge_tags.md), read [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 447 | `KnowledgeService::loadArticleRow` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 464 | `KnowledgeService::setTags` | **delete** [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 470 | `KnowledgeService::setTags` | read [knowledge_tags](/code/tables/knowledge_tags.md) |  |
| 474 | `KnowledgeService::setTags` | **insert** [knowledge_tags](/code/tables/knowledge_tags.md) (`name`, `created_datetime`) |  |
| 477 | `KnowledgeService::setTags` | **insert** [knowledge_article_tags](/code/tables/knowledge_article_tags.md) (`article_id`, `tag_id`) |  |
| 494 | `KnowledgeService::updateEmbedding` | read [system_settings](/code/tables/system_settings.md) |  |
| 526 | `KnowledgeService::updateEmbedding` | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`embedding`, `embedding_updated`) |  |
| 544 | `KnowledgeService::resolveAnalyst` | read [analysts](/code/tables/analysts.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md), [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/search/indexer.php](/code/files/includes/search/indexer.php.md)

**Included by:** [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), [api/knowledge/knowledge_bulk_audience.php](/code/files/api/knowledge/knowledge_bulk_audience.php.md), [api/knowledge/knowledge_delete.php](/code/files/api/knowledge/knowledge_delete.php.md), [api/knowledge/knowledge_save.php](/code/files/api/knowledge/knowledge_save.php.md), [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md), [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [getTenantById()](/code/functions/getTenantById.md), [knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md), [searchCorpusReady()](/code/functions/searchCorpusReady.md), [searchIndexArticle()](/code/functions/searchIndexArticle.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

