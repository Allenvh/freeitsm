# api/knowledge/knowledge_articles.php

> API endpoint - 124 lines

API Endpoint: Get knowledge base articles list

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/knowledge_articles.php` |
| Protected by | Module access: ? (requireAnyModuleAccessJson) |
| Reads | `GET search`, `GET tags`, `GET folder` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 39 |  | read [knowledge_articles](/code/tables/knowledge_articles.md), read [analysts](/code/tables/analysts.md) |  |
| 72 |  | read [knowledge_shortcuts](/code/tables/knowledge_shortcuts.md) |  |
| 87 |  | read [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |
| 99 |  | read [knowledge_tags](/code/tables/knowledge_tags.md), read [knowledge_article_tags](/code/tables/knowledge_article_tags.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireAnyModuleAccessJson()](/code/functions/requireAnyModuleAccessJson.md)

