# api/knowledge/gap_dismiss.php

> API endpoint - 84 lines

API: Knowledge — "we don't need an article for this".

POST { cluster_id: int, undo?: bool }

Dismissing is a real decision, not a snooze: plenty of recurring questions
genuinely should not become articles (a weekly report request, a door that
needs a facilities visit). The dismissal is stored on the cluster and
deliberately survives re-analysis — gapPersistClusters matches clusters by
ticket overlap precisely so that a growing cluster does not silently come
back after someone has already said no.

Undo exists because the assistant will be wrong sometimes and a mis-click
should not cost you a finding for ever.

## The endpoint

| | |
|---|---|
| URL | `api/knowledge/gap_dismiss.php` |
| Protected by | Module access: knowledge (requireModuleAccessJson) |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 54 |  | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 72 |  | **update** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) (`status`, `dismissed_by_id`, `dismissed_datetime`) |  |
| 75 |  | **update** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) (`status`, `dismissed_by_id`, `dismissed_datetime`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/rbac.php](/code/files/includes/rbac.php.md), [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [connectToDatabase()](/code/functions/connectToDatabase.md), [requireModuleAccessJson()](/code/functions/requireModuleAccessJson.md), [writeupSchemaReady()](/code/functions/writeupSchemaReady.md)

