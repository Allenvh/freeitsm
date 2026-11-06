# knowledge_gap_cluster_tickets

## Columns

| Column | Definition | Notes |
|---|---|---|
| `cluster_id` | `INT NOT NULL` |  |
| `ticket_id` | `INT NOT NULL` |  |
| `similarity` | `FLOAT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):507 | `gapPersistClusters` | delete |  |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):508 | `gapPersistClusters` | insert | `cluster_id`, `ticket_id`, `similarity` |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md) | L78 |  |
| [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md) | L133 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapPersistClusters L428 |  |

