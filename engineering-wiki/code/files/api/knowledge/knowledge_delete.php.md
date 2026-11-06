# api/knowledge/knowledge_delete.php

> API endpoint - 27 lines

API Endpoint: Archive (soft delete) knowledge base article. Thin UI adapter over KnowledgeService — moves the article to the recycle bin.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/knowledge_delete.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

