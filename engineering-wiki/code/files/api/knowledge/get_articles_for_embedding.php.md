# api/knowledge/get_articles_for_embedding.php

> API endpoint - 50 lines

API Endpoint: Get Articles for Embedding Returns list of published articles that don't have embeddings yet

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/get_articles_for_embedding.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson); Permission: Cap::KNOWLEDGE_EMBEDDINGS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

