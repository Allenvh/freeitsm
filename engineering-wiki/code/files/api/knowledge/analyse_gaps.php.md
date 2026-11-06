# api/knowledge/analyse_gaps.php

> API endpoint - 94 lines

API: Knowledge — analyse the gaps.

Reads recently closed tickets, asks the knowledge base "do you already answer
this?", and groups the ones it does not into clusters. What the assistant
reports is never "this ticket could be an article" — that is unknowable from
one ticket — but "you have answered this fourteen times and never written it
down", which is a fact about volume and is therefore worth acting on.

POST { action: 'status' | 'embed' | 'cluster', batch?: int }

Chunked because embedding costs a paid API call per ticket and a 90-day window
on a busy desk is thousands of them: the front end loops on 'embed' behind a
progress bar, then calls 'cluster' once. Every step is resumable — vectors are
cached in knowledge_gap_tickets and survive a closed tab.

Thin adapter only. The analysis itself lives in includes/knowledge/gap_analysis.php
so it can be run — and tested — without an HTTP request.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/analyse_gaps.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson); Permission: Cap::KNOWLEDGE_EMBEDDINGS (requireCapabilityJson) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [gapAnalyse()](/code/functions/gapAnalyse.md), [gapEmbedBatch()](/code/functions/gapEmbedBatch.md), [gapWindowCounts()](/code/functions/gapWindowCounts.md), [knowledgeOpenAiKey()](/code/functions/knowledgeOpenAiKey.md), [requireCapabilityJson()](/code/functions/requireCapabilityJson.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [writeupSchemaReady()](/code/functions/writeupSchemaReady.md), [writeupSettings()](/code/functions/writeupSettings.md)

