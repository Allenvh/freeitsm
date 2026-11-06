# api/self-service/get_knowledge_article.php

> API endpoint - 97 lines

API: one Help Centre article, for the self-service portal. GET ?id=

Same two server-derived scopes as the list (see get_knowledge_articles.php):
audience hard-coded to CUSTOMER, company from users.tenant_id.

The scope is applied IN THE QUERY rather than fetched-then-checked, so an
article the requester may not see is indistinguishable from one that does not
exist — the same "no existence oracle" behaviour the analyst endpoint uses,
and the same reason the portal's ticket endpoints all return a flat
"not found".

## The endpoint

| | |
|---|---|
| URL | `api/self-service/get_knowledge_article.php` |
| Protected by | Signed-in analyst (session check) (via includes/rbac.php) |
| Reads | `GET id` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 46 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 61 |  | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_tags](/code/tables/knowledge_tags.md) |  |
| 84 |  | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`view_count`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/knowledge/portal_reader.php](/code/files/includes/knowledge/portal_reader.php.md), [includes/knowledge/help_centre_documents.php](/code/files/includes/knowledge/help_centre_documents.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [helpCentreArticleDocuments()](/code/functions/helpCentreArticleDocuments.md), [portalKnowledgeScope()](/code/functions/portalKnowledgeScope.md), [portalUserTenantId()](/code/functions/portalUserTenantId.md)

