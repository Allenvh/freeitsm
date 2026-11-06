# gapAnalyse()

Score every closed ticket in the window against the knowledge base, cluster what nothing covers, and persist the result.

`$opts`['force_wording'] runs the free engine even when a key exists — used by
the test harness so the clustering logic can be exercised without spending
money on synthetic data.

```php
function gapAnalyse(PDO $conn, int $analystId, array $opts = []): array
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 193.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` | `[]` | optional: lookback_days (int, else the setting) and force_wording (true runs the free wording engine) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 202 | `gapAnalyse` | read [knowledge_gap_tickets](/code/tables/knowledge_gap_tickets.md) |  |
| 233 | `gapAnalyse` | read [knowledge_articles](/code/tables/knowledge_articles.md) |  |

## Calls

[gapBulkRichness()](/code/functions/gapBulkRichness.md), [gapClearOpenClusters()](/code/functions/gapClearOpenClusters.md), [gapPersistClusters()](/code/functions/gapPersistClusters.md), [gapSummarySentence()](/code/functions/gapSummarySentence.md), [gapUpsertTicket()](/code/functions/gapUpsertTicket.md), [gapWindowSql()](/code/functions/gapWindowSql.md), [kbCosineSimilarity()](/code/functions/kbCosineSimilarity.md), [knowledgeOpenAiKey()](/code/functions/knowledgeOpenAiKey.md), [knowledgeVisibilitySql()](/code/functions/knowledgeVisibilitySql.md), [writeupCluster()](/code/functions/writeupCluster.md), [writeupSettings()](/code/functions/writeupSettings.md), [writeupSubjectTokens()](/code/functions/writeupSubjectTokens.md), [writeupTokenSimilarity()](/code/functions/writeupTokenSimilarity.md)

## Called from

- [api/knowledge/analyse_gaps.php](/code/files/api/knowledge/analyse_gaps.php.md)

