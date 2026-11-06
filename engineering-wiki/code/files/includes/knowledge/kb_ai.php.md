# includes/knowledge/kb_ai.php

> PHP - 189 lines

Knowledge-base retrieval for AI answers — the shared RAG building blocks.

Factored out so more than one caller can "ask the Knowledge base" without each
re-implementing embedding search: the Knowledge module's own AI chat
(api/knowledge/ai_chat.php) and the web chat widget's AI answers
(includes/webchat/ai.php). Each caller supplies its OWN system prompt and makes its
own `aiProviderChat()` call — this file only finds the relevant articles and builds the
context block.

NOTE: api/knowledge/ai_chat.php still carries its own equivalent copy for now; when
it's next touched it should be switched to these helpers. Kept separate here to avoid
refactoring a working endpoint as part of the web chat build.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [kbCosineSimilarity()](/code/functions/kbCosineSimilarity.md) | 28 | Cosine similarity of two equal-ish length vectors (0 if either is a zero vector). |
| [kbGenerateEmbedding()](/code/functions/kbGenerateEmbedding.md) | 49 | Embed a query with OpenAI's small embedding model. Returns the vector or null. |
| [kbRetrieveArticles()](/code/functions/kbRetrieveArticles.md) | 117 | Find the Knowledge articles most relevant to a question. Uses embedding similarity when an OpenAI key + embedded articles exist, else falls back to every in-scope article. Returns ['articles' => [{id,title,body}], 'method' => 'vector'\|'all']. |
| [kbBuildContext()](/code/functions/kbBuildContext.md) | 178 | Turn retrieved articles into a plain-text context block for the AI system prompt. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 131 | `kbRetrieveArticles` | read [system_settings](/code/tables/system_settings.md) |  |
| 141 | `kbRetrieveArticles` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 149 | `kbRetrieveArticles` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 167 | `kbRetrieveArticles` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## How it hangs together

**Includes:** [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md)

**Included by:** [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), [includes/webchat/ai.php](/code/files/includes/webchat/ai.php.md)

**Calls:** [decryptValue()](/code/functions/decryptValue.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [sslApplyCurl()](/code/functions/sslApplyCurl.md)

