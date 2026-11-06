# includes/knowledge/gap_analysis.php

> PHP - 562 lines

Knowledge gap analysis — the half of the assistant that decides WHAT is missing.

Split out of api/knowledge/analyse_gaps.php so the analysis can be run without
an HTTP request and a session. That is not architectural tidiness for its own
sake: the interesting behaviour here is emergent (does a pile of real tickets
actually cluster into the right questions?) and the only way to test it is to
build a known pile inside a transaction, run the analysis on the SAME database
connection, assert, and roll back. Over HTTP that is impossible — the endpoint
gets its own connection and cannot see the uncommitted rows.

See tests/knowledge-gaps/ for the harness that does exactly that.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [knowledgeOpenAiKey()](/code/functions/knowledgeOpenAiKey.md) | 30 | The OpenAI key used for ARTICLE embeddings, reused deliberately. |
| [gapWindowSql()](/code/functions/gapWindowSql.md) | 50 | The closed-ticket window this analyst can see. |
| [gapUpsertTicket()](/code/functions/gapUpsertTicket.md) | 72 | Insert-or-update one knowledge_gap_tickets row from a partial field map. |
| [gapWindowCounts()](/code/functions/gapWindowCounts.md) | 91 | How many tickets are in the window, and how many already have an embedding. |
| [gapEmbedBatch()](/code/functions/gapEmbedBatch.md) | 120 | Embed up to `$batch` not-yet-embedded tickets. Returns a progress report. |
| [gapAnalyse()](/code/functions/gapAnalyse.md) | 193 | Score every closed ticket in the window against the knowledge base, cluster what nothing covers, and persist the result. |
| [gapBulkRichness()](/code/functions/gapBulkRichness.md) | 360 | Approximate richness for many tickets in four queries. |
| [gapPersistClusters()](/code/functions/gapPersistClusters.md) | 425 | Persist clusters, preserving what the analyst has already decided about them. |
| [gapClearOpenClusters()](/code/functions/gapClearOpenClusters.md) | 538 | Clear the open cards but keep the analyst's decisions. |
| [gapSummarySentence()](/code/functions/gapSummarySentence.md) | 550 | The assistant's opening line, in plain English. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 33 | `knowledgeOpenAiKey` | read [system_settings](/code/tables/system_settings.md) |  |
| 58 | `gapWindowSql` | read [tickets](/code/tables/tickets.md), read [ticket_statuses](/code/tables/ticket_statuses.md) |  |
| 81 | `gapUpsertTicket` | **insert** [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 100 | `gapWindowCounts` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 130 | `gapEmbedBatch` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 166 | `gapEmbedBatch` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 202 | `gapAnalyse` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 233 | `gapAnalyse` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |
| 378 | `gapBulkRichness` | read [emails](/code/tables/emails.md) |  |
| 388 | `gapBulkRichness` | read [ticket_notes](/code/tables/ticket_notes.md) |  |
| 395 | `gapBulkRichness` | read [ticket_time_entries](/code/tables/ticket_time_entries.md) |  |
| 402 | `gapBulkRichness` | read [problem_tickets](/code/tables/problem_tickets.md) |  |
| 428 | `gapPersistClusters` | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md), read [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md) |  |
| 494 | `gapPersistClusters` | **update** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 501 | `gapPersistClusters` | **insert** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 507 | `gapPersistClusters` | **delete** [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md) |  |
| 508 | `gapPersistClusters` | **insert** [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md) (`cluster_id`, `ticket_id`, `similarity`) |  |
| 516 | `gapPersistClusters` | **update** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) (`status`, `article_id`) |  |
| 528 | `gapPersistClusters` | **delete** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 531 | `gapPersistClusters` | **delete** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 540 | `gapClearOpenClusters` | **delete** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |

## How it hangs together

**Includes:** [includes/knowledge/writeup_ai.php](/code/files/includes/knowledge/writeup_ai.php.md), [includes/knowledge/kb_ai.php](/code/files/includes/knowledge/kb_ai.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/tenancy.php](/code/files/includes/tenancy.php.md), [includes/knowledge/visibility.php](/code/files/includes/knowledge/visibility.php.md), [includes/ticket_sensitivity.php](/code/files/includes/ticket_sensitivity.php.md)

**Included by:** [api/knowledge/analyse_gaps.php](/code/files/api/knowledge/analyse_gaps.php.md)

**Calls:** [activeTenantFilter()](/code/functions/activeTenantFilter.md), [decryptValue()](/code/functions/decryptValue.md), [kbCosineSimilarity()](/code/functions/kbCosineSimilarity.md), [kbGenerateEmbedding()](/code/functions/kbGenerateEmbedding.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [ticketAiExclusionSql()](/code/functions/ticketAiExclusionSql.md), [writeupCleanLabel()](/code/functions/writeupCleanLabel.md), [writeupCluster()](/code/functions/writeupCluster.md), [writeupEmbeddingText()](/code/functions/writeupEmbeddingText.md), [writeupRichness()](/code/functions/writeupRichness.md), [writeupSettings()](/code/functions/writeupSettings.md), [writeupSubjectTokens()](/code/functions/writeupSubjectTokens.md), [writeupTokenSimilarity()](/code/functions/writeupTokenSimilarity.md)

