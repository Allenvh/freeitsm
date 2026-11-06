# gapPersistClusters()

Persist clusters, preserving what the analyst has already decided about them.

🔑 A freshly computed cluster is matched to a stored one by TICKET OVERLAP,
not by id, seed or label. A cluster grows as new tickets close and its seed
changes as richer examples arrive, so any identity built on "same seed" or
"same subject" breaks on the next run and re-raises something the analyst has
already dismissed. Overlap survives both.

```php
function gapPersistClusters(PDO $conn, array $clusters): int
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 425.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$clusters` | `array` |  | clusters from `writeupCluster()` over `gapAnalyse()`'s candidates (seed + members of {item, similarity}) |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 428 | `gapPersistClusters` | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md), read [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md) |  |
| 494 | `gapPersistClusters` | **update** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 501 | `gapPersistClusters` | **insert** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 507 | `gapPersistClusters` | **delete** [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md) |  |
| 508 | `gapPersistClusters` | **insert** [knowledge_gap_cluster_tickets](/code/tables/knowledge_gap_cluster_tickets.md) (`cluster_id`, `ticket_id`, `similarity`) |  |
| 516 | `gapPersistClusters` | **update** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) (`status`, `article_id`) |  |
| 528 | `gapPersistClusters` | **delete** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |
| 531 | `gapPersistClusters` | **delete** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |

## Calls

[writeupCleanLabel()](/code/functions/writeupCleanLabel.md)

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

