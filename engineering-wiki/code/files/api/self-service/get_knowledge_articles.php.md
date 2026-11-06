# api/self-service/get_knowledge_articles.php

> API endpoint - 129 lines

API: Help Centre article list / search, for the self-service portal. GET ?q=&limit=

The FIRST reader at the Audience::CUSTOMER rung. That rung has been built,
tested and documented since #869 with nothing reading it — the editor has had
to label it "(not yet in use)".

TWO SCOPES, BOTH SERVER-DERIVED, NEITHER ACCEPTED FROM THE REQUEST:

  1. AUDIENCE is hard-coded to CUSTOMER. It is never a parameter — the same
     rule the web chat reader follows (includes/webchat/ai.php), for the same
     reason: whoever is asking does not get to say how trusted they are.
     CUSTOMER also includes PUBLIC, since the ladder is cumulative.

  2. COMPANY comes from users.tenant_id, looked up here. The portal session
     holds only ss_user_id.

⚠️ `knowledgeTenantFilterForCompany()` — NOT `activeTenantFilter()`. For knowledge
a NULL tenant_id means SHARED WITH EVERY COMPANY, the opposite of tickets and
assets. Feeding a non-Default company through the ticket-shaped helper makes
every shared article silently vanish. This trap has bitten twice.

Archiving does NOT unpublish, so both flags are checked (KB_VISIBLE_SQL) —
omitting the archive check is exactly how deleted articles once reached
anonymous web chat visitors.

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_knowledge_articles.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php) |
| Reads | `GET q`, `GET sort`, `GET limit` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 72 |  | read [knowledge_articles](/code/tables/knowledge_articles.md), read [knowledge_folders](/code/tables/knowledge_folders.md) |  |
| 91 |  | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_tags](/code/tables/knowledge_tags.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md), [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [helpCentreDocumentCounts()](/code/functions/helpCentreDocumentCounts.md), [portalArticlePreview()](/code/functions/portalArticlePreview.md), [portalKnowledgeScope()](/code/functions/portalKnowledgeScope.md), [portalUserTenantId()](/code/functions/portalUserTenantId.md)

