# api/v1/resources/knowledge.php

> API endpoint - 426 lines

FreeITSM REST API v1 — knowledge resource (Knowledge Base).

Mirrors the module's internal endpoints so an article touched via the API
is indistinguishable from one touched in the UI:
  - create/update mirror api/knowledge/knowledge_save.php: articles are
    created published (there is no draft workflow in the product), tags are
    get-or-created by name and re-linked as a set, "save as version" first
    snapshots the current row into knowledge_article_versions and bumps the
    version number, and — when the module's OpenAI key is configured — the
    search EMBEDDING is regenerated after the save (best-effort, silent on
    failure), so AI chat/vector search keeps working for API-written
    articles.
  - delete/restore/purge mirror the recycle-bin semantics
    (knowledge_delete.php / knowledge_archive.php): DELETE archives,
    restore un-archives, permanent delete only works on archived articles
    and cleans up orphaned tags; listing the bin runs the same retention
    auto-purge the UI runs when the bin is opened.

Deliberate divergence from the UI, documented: GET /knowledge/articles/{id}
does NOT bump view_count unless ?count_view=true is passed — machine reads
(sync jobs, chatbots re-fetching) would otherwise inflate the stats the
review screens rely on.

Articles carry a `company` (knowledge_articles.tenant_id; null = shared with
every company) and an `audience` (internal | customer | public). Lists are
scoped to the key's companies via `apiKeyKnowledgeFilter()` — NOT the generic
`apiKeyTenantFilter()`, because a NULL tenant_id here means "shared with all"
rather than "the Default company's"; see that function's docblock. Bodies are
TinyMCE HTML stored verbatim, exactly like the UI (no server-side
sanitisation exists in the product) — consumers render at their own risk,
and integrations should send trusted HTML only.

Article WRITES (create/update/archive/restore/purge) are delegated to
KnowledgeService (includes/services/knowledge.php); the read handlers,
serializers + the bin's retention auto-purge stay here.

## The endpoint

| | |
|---|---|
| URL | `api/v1/resources/knowledge.php` |
| Protected by | REST API key |
| Reads | `GET archived`, `GET q`, `GET tag`, `GET review`, `GET modified_since`, `GET company`, `GET audience`, `GET sort`, `GET count_view` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [apiKnowledgeSelect()](/code/functions/apiKnowledgeSelect.md) | 50 | The SELECT for knowledge articles, with author, owner, archiver and company names. |
| [apiKnowledgeTags()](/code/functions/apiKnowledgeTags.md) | 68 | The names of an article's tags, alphabetically. |
| [apiSerializeArticle()](/code/functions/apiSerializeArticle.md) | 84 | Summary shape (lists) — preview instead of the full body. Detail adds body_html. |
| [apiLoadArticle()](/code/functions/apiLoadArticle.md) | 129 | Load an article by id, 404ing if it's gone. |
| [apiKnowledgePurgeExpired()](/code/functions/apiKnowledgePurgeExpired.md) | 148 | The recycle bin's retention auto-purge (mirrors knowledge_archive.php). |
| [apiKnowledgeArticlesList()](/code/functions/apiKnowledgeArticlesList.md) | 175 | GET /knowledge/articles |
| [apiKnowledgeArticlesGet()](/code/functions/apiKnowledgeArticlesGet.md) | 293 | GET /knowledge/articles/{id} |
| [apiKnowledgeArticlesCreate()](/code/functions/apiKnowledgeArticlesCreate.md) | 309 | POST /knowledge/articles |
| [apiKnowledgeArticlesUpdate()](/code/functions/apiKnowledgeArticlesUpdate.md) | 319 | PATCH /knowledge/articles/{id} |
| [apiKnowledgeArticlesDelete()](/code/functions/apiKnowledgeArticlesDelete.md) | 329 | DELETE (archive) / restore / permanent purge — the recycle-bin semantics |
| [apiKnowledgeArticlesRestore()](/code/functions/apiKnowledgeArticlesRestore.md) | 340 | POST /knowledge/articles/{id}/restore - brings an article back from the recycle bin through KnowledgeService and returns it. |
| [apiKnowledgeArticlesPurge()](/code/functions/apiKnowledgeArticlesPurge.md) | 351 | DELETE /knowledge/articles/{id}/permanent - permanently deletes an archived article through KnowledgeService. |
| [apiKnowledgeVersionsList()](/code/functions/apiKnowledgeVersionsList.md) | 361 | Versions |
| [apiKnowledgeVersionsGet()](/code/functions/apiKnowledgeVersionsGet.md) | 383 | GET /knowledge/articles/{id}/versions/{version} - one saved version of an article: title, body HTML, who saved it and when. |
| [apiKnowledgeTagsList()](/code/functions/apiKnowledgeTagsList.md) | 407 | GET /knowledge/tags — tag list with published article counts |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 51 | `apiKnowledgeSelect` | read [knowledge_articles](/code/tables/knowledge_articles.md), read [analysts](/code/tables/analysts.md), read [tenants](/code/tables/tenants.md) |  |
| 70 | `apiKnowledgeTags` | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_tags](/code/tables/knowledge_tags.md) |  |
| 150 | `apiKnowledgePurgeExpired` | read [system_settings](/code/tables/system_settings.md) |  |
| 158 | `apiKnowledgePurgeExpired` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 164 | `apiKnowledgePurgeExpired` | **delete** [knowledge_article_versions](/code/tables/knowledge_article_versions.md) |  |
| 165 | `apiKnowledgePurgeExpired` | **delete** [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 166 | `apiKnowledgePurgeExpired` | **delete** [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 167 | `apiKnowledgePurgeExpired` | **delete** [knowledge_tags](/code/tables/knowledge_tags.md), read [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 192 | `apiKnowledgeArticlesList` | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_tags](/code/tables/knowledge_tags.md) |  |
| 272 | `apiKnowledgeArticlesList` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 299 | `apiKnowledgeArticlesGet` | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`view_count`) |  |
| 364 | `apiKnowledgeVersionsList` | read [knowledge_article_versions](/code/tables/knowledge_article_versions.md), read [analysts](/code/tables/analysts.md) |  |
| 386 | `apiKnowledgeVersionsGet` | read [knowledge_article_versions](/code/tables/knowledge_article_versions.md), read [analysts](/code/tables/analysts.md) |  |
| 409 | `apiKnowledgeTagsList` | read [knowledge_tags](/code/tables/knowledge_tags.md), read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/service_context.php](/code/files/includes/service_context.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md), [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [api/v1/index.php](/code/files/api/v1/index.php.md)

**Calls:** [apiError()](/code/functions/apiError.md), [apiFailFromService()](/code/functions/apiFailFromService.md), [apiIsoDate()](/code/functions/apiIsoDate.md), [apiKeyCanAccessTenant()](/code/functions/apiKeyCanAccessTenant.md), [apiPagination()](/code/functions/apiPagination.md), [apiParseDate()](/code/functions/apiParseDate.md), [apiRespond()](/code/functions/apiRespond.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

