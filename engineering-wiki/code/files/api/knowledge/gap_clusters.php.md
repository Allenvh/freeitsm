# api/knowledge/gap_clusters.php

> API endpoint - 151 lines

API: Knowledge — what the assistant found.

GET ?status=open|dismissed|written|all  (default open)

Returns the gap clusters plus the sample tickets behind each one, so the
analyst can check the assistant's reasoning before acting on it. Showing the
evidence matters: "you have been asked this 14 times" is only persuasive if
you can click through and see the fourteen.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/gap_clusters.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |
| Reads | `GET status` |

## Functions

| Function | Line | What it does |
|---|--:|---|
| [gapStatusCounts()](/code/functions/gapStatusCounts.md) | 139 | How many clusters sit in each state, for the view's tabs. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 |  | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md), read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 78 |  | read [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md), read [tickets](/code/tables/tickets.md) |  |
| 119 |  | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 143 | `gapStatusCounts` | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [writeupSchemaReady()](/code/functions/writeupSchemaReady.md)

