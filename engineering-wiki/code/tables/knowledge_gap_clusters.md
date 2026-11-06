# knowledge_gap_clusters

## Columns

| Column | Definition | Notes |
|---|---|---|
| `id` | `INT NOT NULL AUTO_INCREMENT` |  |
| `label` | `VARCHAR(255) NOT NULL` |  |
| `seed_ticket_id` | `INT NULL` |  |
| `best_ticket_id` | `INT NULL` | The ticket the assistant drafts FROM — the richest in the cluster, not the newest. A thin ticket counts towards the total but is never handed to the model as the source. |
| `max_richness` | `INT NOT NULL DEFAULT 0` |  |
| `ticket_count` | `INT NOT NULL DEFAULT 0` |  |
| `first_ticket_datetime` | `DATETIME NULL` |  |
| `last_ticket_datetime` | `DATETIME NULL` |  |
| `status` | `VARCHAR(20) NOT NULL DEFAULT 'open'` | open \| dismissed \| written. 'dismissed' has to survive a re-analysis, which is the whole reason clusters are stored rather than recomputed on every view. |
| `dismissed_by_id` | `INT NULL` |  |
| `dismissed_datetime` | `DATETIME NULL` |  |
| `article_id` | `INT NULL` |  |
| `signature` | `VARCHAR(64) NULL` |  |
| `tenant_id` | `INT NULL` |  |
| `created_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP` |  |
| `updated_datetime` | `DATETIME NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [api/knowledge/gap_dismiss.php](/code/files/api/knowledge/gap_dismiss.php.md):72 |  | update | `status`, `dismissed_by_id`, `dismissed_datetime` |  |
| [api/knowledge/gap_dismiss.php](/code/files/api/knowledge/gap_dismiss.php.md):75 |  | update | `status`, `dismissed_by_id`, `dismissed_datetime` |  |
| [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md):84 |  | update | `status`, `article_id` |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):494 | `gapPersistClusters` | update |  |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):501 | `gapPersistClusters` | insert |  |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):516 | `gapPersistClusters` | update | `status`, `article_id` |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):528 | `gapPersistClusters` | delete |  |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):531 | `gapPersistClusters` | delete |  |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):540 | `gapClearOpenClusters` | delete |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md) | L60, gapStatusCounts L143 |  |
| [api/knowledge/gap_dismiss.php](/code/files/api/knowledge/gap_dismiss.php.md) | L54 |  |
| [api/knowledge/writeup_save.php](/code/files/api/knowledge/writeup_save.php.md) | L81 |  |
| [api/knowledge/writeup_stream.php](/code/files/api/knowledge/writeup_stream.php.md) | L109 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapPersistClusters L428 |  |

