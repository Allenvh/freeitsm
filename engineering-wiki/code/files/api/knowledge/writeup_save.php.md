# api/knowledge/writeup_save.php

> API endpoint - 101 lines

API: Knowledge — save the assistant's draft.

POST { title, body_html, ticket_id?, cluster_id? }

Creates an UNPUBLISHED article. That is the point: the assistant proposes, a
human decides. Nothing an AI wrote reaches a reader — least of all a
customer-facing portal — until somebody has read it and pressed publish.

Goes through KnowledgeService rather than its own INSERT so drafts get the
same validation, company scoping, audience default and embedding regeneration
as every other article. The one thing it asks for that no other caller does is
is_published => false.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/writeup_save.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 63 |  | read [tickets](/code/tables/tickets.md) |  |
| 81 |  | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 84 |  | **update** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) (`status`, `article_id`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/services/knowledge.php](/code/files/includes/services/knowledge.php.md), [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [analystCanAccessTicket()](/code/functions/analystCanAccessTicket.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [writeupSchemaReady()](/code/functions/writeupSchemaReady.md)

