# api/knowledge/knowledge_tags.php

> API endpoint - 64 lines

API Endpoint: Get all knowledge base tags with article counts

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/knowledge_tags.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md), read [knowledge_articles](/code/tables/knowledge_articles.md), read [knowledge_tags](/code/tables/knowledge_tags.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

