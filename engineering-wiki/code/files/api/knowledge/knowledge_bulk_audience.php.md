# api/knowledge/knowledge_bulk_audience.php

> API endpoint - 94 lines

API: set "who can see this" on several articles at once. POST { ids: [1,2,3], audience: 'internal'|'customer'|'public' }

WHY THIS EXISTS
---------------
Every article defaults to `internal` — deliberately, so that adding the
audience column (#868) could never start disclosing anything nobody had
chosen to publish. The consequence is that the moment the self-service Help
Centre shipped, EVERY install had an empty one: publishing a knowledge base
meant opening articles one at a time.

IMPLEMENTATION
--------------
A thin loop over KnowledgeService::saveArticle(), which already does exactly
what is needed and nothing more: it updates `audience` ONLY when that key is
present, it REJECTS an invalid audience outright rather than normalising it
down to internal (so nobody saves as internal while believing they published),
and it refuses to touch an article belonging to a company the actor cannot
reach. Hand-rolling an UPDATE here would have bypassed all three.

PARTIAL SUCCESS IS REPORTED, NOT SWALLOWED. Selecting fifty articles and
having three silently skipped — because they belong to another company — is
how someone ends up believing a document is published when it is not. The
response names what failed.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/knowledge_bulk_audience.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |
| Called from | [assets/js/knowledge.js](/code/files/assets/js/knowledge.js.md) |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md), [includes/knowledge/audience.php](/code/files/includes/knowledge/audience.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md)

