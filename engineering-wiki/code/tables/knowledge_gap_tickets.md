# knowledge_gap_tickets

Knowledge gaps — the Knowledge assistant. One closed ticket says almost nothing ("reset password, asked user to log in again" is not an article); fourteen of them is a different statement. This is a CACHE — every row can be dropped and rebuilt by re-running the analysis. It exists only because embedding a ticket costs a paid API call.

## Columns

| Column | Definition | Notes |
|---|---|---|
| `ticket_id` | `INT NOT NULL` |  |
| `embedding` | `LONGTEXT NULL` |  |
| `embedded_datetime` | `DATETIME NULL` |  |
| `best_article_id` | `INT NULL` | Similarity to the CLOSEST published article — low means nothing in the KB answers this ticket, which is what makes it a gap candidate. |
| `best_similarity` | `FLOAT NULL` |  |
| `richness` | `INT NOT NULL DEFAULT 0` | 0-100: how much of an article could actually be written from this one ticket. Picks which ticket in a cluster gets drafted from, and decides whether the assistant has to interview the analyst instead. |
| `analysed_datetime` | `DATETIME NULL` |  |
| `tenant_id` | `INT NULL` |  |

## Written by

| File | In | Does | Columns | Purpose |
|---|---|---|---|---|
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md):81 | `gapUpsertTicket` | insert |  |  |

## Read by

| File | Where | Purpose |
|---|---|---|
| [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md) | L119 |  |
| [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) | gapWindowCounts L100, gapEmbedBatch L130, gapEmbedBatch L166, gapAnalyse L202 |  |

