# includes/knowledge/visibility.php

> PHP - 914 lines

> [!TIP]
> **Developer guide on the GitHub wiki:** [Knowledge Folders and Permissions](https://github.com/edmozley/freeitsm/wiki/Knowledge-Folders-and-Permissions)

Who may READ a Knowledge article — the single place that answers it.

─────────────────────────────────────────────────────────────────────────────
WHY THIS FILE EXISTS
─────────────────────────────────────────────────────────────────────────────
Knowledge had no choke point. Roughly 48 raw `SELECT ... FROM
knowledge_articles` statements across ~30 files each assembled their own
visibility clause out of three separate ingredients:

  1. lifecycle   is_published / is_archived   (KB_VISIBLE_SQL in kb_ai.php)
  2. company     knowledgeTenantFilter*()     (tenancy.php)
  3. audience    Audience::sqlFilter()        (audience.php)

Nothing forced a caller to use all three, or any. That was survivable while
the axes were coarse; it stops being survivable the moment an article can be
restricted to named people, because then a forgotten clause is a disclosure
rather than an inconsistency.

So: every read path goes through this file, and the three ingredients (plus
the access list, when it lands) are combined here exactly once.

⚠️ THIS FILE MUST NOT CHANGE BEHAVIOUR ON ARRIVAL. It composes the existing
helpers rather than reimplementing them, so migrating a caller onto it is a
refactor with an identical result set. That property is what makes the
migration verifiable — tests/knowledge-visibility/ must stay green untouched.

─────────────────────────────────────────────────────────────────────────────
THE RULE
─────────────────────────────────────────────────────────────────────────────
  Every axis NARROWS. Nothing widens.

An article is readable only if ALL of them agree. The axes are ANDed, never
weighed against each other, so there is no precedence question to answer —
and an access-list grant can never reach past the audience ladder. See
https://github.com/edmozley/freeitsm/wiki/Knowledge-Folders-and-Permissions

─────────────────────────────────────────────────────────────────────────────
THE TWO ENTRY POINTS
─────────────────────────────────────────────────────────────────────────────
  `knowledgeVisibilitySql()`  — a clause to AND into a list query
  `knowledgeCanRead()`        — the boundary check for a single id

The second is implemented in terms of the first ON PURPOSE. A list and a
fetch that build their filters separately drift, and the drift always
resolves the same way: the list hides an article the fetch will happily
serve to anyone holding its id. One clause, two uses, no drift.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md) | 346 | The SQL clause restricting a knowledge_articles query to what `$viewer` may read. |
| [knowledgeCanRead()](/code/functions/knowledgeCanRead.md) | 435 | May `$viewer` read article `$articleId`? |
| [knowledgeViewerHasAdminFloor()](/code/functions/knowledgeViewerHasAdminFloor.md) | 485 | Does this viewer hold the administrator floor? |
| [knowledgeAuditAdminOverride()](/code/functions/knowledgeAuditAdminOverride.md) | 502 | Record that the floor let somebody past an access list they were not on. |
| [knowledgeAclSql()](/code/functions/knowledgeAclSql.md) | 540 | The access-list clause. Returns ['', []] until the folder/ACL tables exist, which is why introducing this file changes nothing. |
| [knowledgeNodePermits()](/code/functions/knowledgeNodePermits.md) | 659 | Does this node let this viewer through? |
| [knowledgeFolderReadable()](/code/functions/knowledgeFolderReadable.md) | 688 | May this viewer reach `$folderId`? |
| [knowledgeViewerPrincipals()](/code/functions/knowledgeViewerPrincipals.md) | 721 | Everything the viewer counts as, as "type:id" strings for cheap lookup. |
| [knowledgeAclResetCaches()](/code/functions/knowledgeAclResetCaches.md) | 809 | Forget everything memoised about folders and the access list. |
| [knowledgeFolderPermissionModel()](/code/functions/knowledgeFolderPermissionModel.md) | 815 | Which permission model this install runs. Default 'containers' — the safe end. |
| [knowledgeAclHasAnyRows()](/code/functions/knowledgeAclHasAnyRows.md) | 842 | Is ANYTHING restricted on this install? The cheap test that skips all the work. |
| [knowledgeAclIndex()](/code/functions/knowledgeAclIndex.md) | 860 | All access rows as [type][object_id]['principal:id' => true]. One query. |
| [knowledgeFolderIndex()](/code/functions/knowledgeFolderIndex.md) | 875 | The folder tree as [id => ['parent'=>?int,'restricted'=>int,'inherit'=>int]]. |
| [knowledgeAclTablesExist()](/code/functions/knowledgeAclTablesExist.md) | 901 | Do the folder/access-list tables exist yet? |

## Classes

- [KnowledgeViewer](/code/classes/KnowledgeViewer.md) (class, line 75) - WHO is asking. Not the same thing as ActorContext (service_context.php), which models an *analyst* performing a write — a Knowledge reader may be a portal user or an anonymous web-chat visitor, neither of whom has an analyst id at all.
- [KnowledgeAclCache](/code/classes/KnowledgeAclCache.md) (class, line 770) - Per-request memo for the four things the access list needs to look up: the permission model, whether any rules exist at all, the rules themselves, and the folder tree. Read once per request rather than once per query — a page that lists articles and then fetches one would otherwise ask four times over.

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 442 | `knowledgeCanRead` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 599 | `knowledgeAclSql` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 732 | `knowledgeViewerPrincipals` | read [analyst_teams](/code/tables/analyst_teams.md) |  |
| 746 | `knowledgeViewerPrincipals` | read [knowledge_user_group_members](/code/tables/knowledge_user_group_members.md), read [knowledge_user_groups](/code/tables/knowledge_user_groups.md) |  |
| 820 | `knowledgeFolderPermissionModel` | read [system_settings](/code/tables/system_settings.md) |  |
| 847 | `knowledgeAclHasAnyRows` | read [knowledge_acl](/code/tables/knowledge_acl.md) |  |
| 848 | `knowledgeAclHasAnyRows` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 849 | `knowledgeAclHasAnyRows` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 866 | `knowledgeAclIndex` | read [knowledge_acl](/code/tables/knowledge_acl.md) |  |
| 881 | `knowledgeFolderIndex` | read [knowledge_folders](/code/tables/knowledge_folders.md) |  |

## How it hangs together

**Includes:** [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/capabilities.php](/code/files/includes/capabilities.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/knowledge/audit.php](/code/files/includes/knowledge/audit.php.md)

**Included by:** [api/files/shortcut.php](/code/files/api/files/shortcut.php.md), [api/knowledge/ai_chat.php](/code/files/api/knowledge/ai_chat.php.md), [api/knowledge/audit.php](/code/files/api/knowledge/audit.php.md), [api/knowledge/folders.php](/code/files/api/knowledge/folders.php.md), [api/knowledge/generate_embedding.php](/code/files/api/knowledge/generate_embedding.php.md), [api/knowledge/get_articles_for_embedding.php](/code/files/api/knowledge/get_articles_for_embedding.php.md), [api/knowledge/get_embedding_stats.php](/code/files/api/knowledge/get_embedding_stats.php.md), [api/knowledge/get_review_list.php](/code/files/api/knowledge/get_review_list.php.md), [api/knowledge/knowledge_archive.php](/code/files/api/knowledge/knowledge_archive.php.md), [api/knowledge/knowledge_article.php](/code/files/api/knowledge/knowledge_article.php.md), [api/knowledge/knowledge_articles.php](/code/files/api/knowledge/knowledge_articles.php.md), [api/knowledge/knowledge_tags.php](/code/files/api/knowledge/knowledge_tags.php.md), [api/knowledge/permissions.php](/code/files/api/knowledge/permissions.php.md), [api/knowledge/permission_model.php](/code/files/api/knowledge/permission_model.php.md), [api/system/global_search.php](/code/files/api/system/global_search.php.md), [api/v1/resources/knowledge.php](/code/files/api/v1/resources/knowledge.php.md), [includes/documents.php](/code/files/includes/documents.php.md), [includes/domains/links.php](/code/files/includes/domains/links.php.md), [includes/files/api_bootstrap.php](/code/files/includes/files/api_bootstrap.php.md), [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md), [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md), [includes/mcp/tools.php](/code/files/includes/mcp/tools.php.md), [includes/projects/links.php](/code/files/includes/projects/links.php.md), [includes/recent_trail.php](/code/files/includes/recent_trail.php.md), [includes/record_preview.php](/code/files/includes/record_preview.php.md), [includes/warbot/tools.php](/code/files/includes/warbot/tools.php.md), [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md)

**Calls:** [analystHasCapability()](/code/functions/analystHasCapability.md), [getAccessibleTenantIds()](/code/functions/getAccessibleTenantIds.md), [getActiveTenantId()](/code/functions/getActiveTenantId.md), [isActiveTenantAll()](/code/functions/isActiveTenantAll.md), [isMultiTenant()](/code/functions/isMultiTenant.md), [knowledgeAuditLog()](/code/functions/knowledgeAuditLog.md), [tenancyColumnExists()](/code/functions/tenancyColumnExists.md)

