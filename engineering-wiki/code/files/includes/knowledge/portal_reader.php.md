# includes/knowledge/portal_reader.php

> PHP - 158 lines

What a self-service requester may read of the knowledge base — in ONE place.

WHY THIS EXISTS
---------------
Three portal surfaces now read articles: the Help Centre list, the single
article, and the dashboard's popular list — with ticket deflection reusing the
list endpoint. Each needs the same three-part scope:

    published AND not archived          — the article is live
    AND audience >= customer            — it is meant for customers
    AND (their company OR shared)       — it belongs to them

That was already copy-pasted across two endpoints before this file existed.
Duplicated scope is the dangerous kind of duplication: nothing breaks when the
copies drift, one of them just quietly starts showing more than it should.
Adding a third copy for the dashboard is what prompted pulling it out.

TWO TRAPS THIS ENCODES SO CALLERS CANNOT GET THEM WRONG
-------------------------------------------------------
1. ⚠️ `knowledgeTenantFilterForCompany()`, never `activeTenantFilter()`. For
   knowledge a NULL tenant_id means SHARED WITH EVERY COMPANY — the opposite
   of tickets and assets, where NULL means the Default company's. Feeding a
   non-Default company through the ticket-shaped helper makes every shared
   article silently vanish. This has bitten twice.
2. ⚠️ Archiving does NOT unpublish. Both flags must be checked; omitting the
   archive half is exactly how deleted articles once reached anonymous web
   chat visitors.

The audience is HARD-CODED to CUSTOMER here and is not a parameter. Whoever is
asking does not get to declare how trusted they are — the same rule the web
chat reader follows (includes/webchat/ai.php).

## Functions

| Function | Line | What it does |
|---|--:|---|
| [portalUserTenantId()](/code/functions/portalUserTenantId.md) | 49 | The company a portal user belongs to, or null for "shared articles only". |
| [portalKnowledgeScope()](/code/functions/portalKnowledgeScope.md) | 71 | The WHERE fragment and bound params for "articles this requester may read". |
| [portalCanReadArticle()](/code/functions/portalCanReadArticle.md) | 101 | May this portal requester read this article in the Help Centre? |
| [portalArticlePreview()](/code/functions/portalArticlePreview.md) | 134 | Turn an article body into a short plain-text preview. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 50 | `portalUserTenantId` | read [users](/code/tables/users.md) |  |
| 104 | `portalCanReadArticle` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [api/self-service/get_knowledge_article.php](/code/files/api/self-service/get_knowledge_article.php.md), [api/self-service/get_knowledge_articles.php](/code/files/api/self-service/get_knowledge_articles.php.md), [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md)

**Calls:** [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md)

