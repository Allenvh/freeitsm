# api/knowledge/get_review_list.php

> API endpoint - 109 lines

API Endpoint: Get knowledge articles for review

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/get_review_list.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |
| Reads | `GET filter` |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 34 |  | read [knowledge_articles](/code/tables/knowledge_articles.md), read [analysts](/code/tables/analysts.md) |  |
| 88 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

