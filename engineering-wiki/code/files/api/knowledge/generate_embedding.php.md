# api/knowledge/generate_embedding.php

> API endpoint - 143 lines

API Endpoint: Generate Embedding Generates an OpenAI embedding for a knowledge article and stores it

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/generate_embedding.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson); Permission: Cap::KNOWLEDGE_EMBEDDINGS (requireCapabilityJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 59 |  | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 129 |  | **update** [knowledge_articles](/code/tables/knowledge_articles.md) (`embedding`, `embedding_updated`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [knowledgeCanRead()](/code/functions/knowledgeCanRead.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

