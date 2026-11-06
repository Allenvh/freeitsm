# api/knowledge/knowledge_article.php

> API endpoint - 97 lines

API Endpoint: Get single knowledge base article

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/knowledge_article.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET id`, `GET include_archived` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [knowledge_articles](/code/tables/knowledge_articles.md), read [analysts](/code/tables/analysts.md) |  |
| 69 |  | read [knowledge_tags](/code/tables/knowledge_tags.md), read [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 79 |  | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`view_count`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

