# gapClearOpenClusters()

Clear the open cards but keep the analyst's decisions.

```php
function gapClearOpenClusters(PDO $conn): void
```

Defined in [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md), line 538.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 540 | `gapClearOpenClusters` | **delete** [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |

## Called from

- [includes/knowledge/gap_analysis.php](/code/files/includes/knowledge/gap_analysis.php.md) - in [gapAnalyse()](/code/functions/gapAnalyse.md)

