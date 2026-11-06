# api/knowledge/knowledge_save.php

> API endpoint - 46 lines

API Endpoint: Save knowledge base article (create or update). Thin UI adapter over KnowledgeService. encryption.php is required so the service's embedding-regeneration step has `decryptValue()` available.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/knowledge_save.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

