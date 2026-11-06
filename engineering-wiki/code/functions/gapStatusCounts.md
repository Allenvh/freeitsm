# gapStatusCounts()

How many clusters sit in each state, for the view's tabs.

```php
function gapStatusCounts(PDO $conn, string $tSql, array $tArgs): array
```

Defined in [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md), line 139.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$tSql` | `string` |  | the company filter SQL from `activeTenantFilter()` on alias c, starting with AND (or empty) |
| `$tArgs` | `array` |  | the values bound to the placeholders in `$tSql` |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 143 | `gapStatusCounts` | read [knowledge_gap_clusters](/code/tables/knowledge_gap_clusters.md) |  |

## Called from

- [api/knowledge/gap_clusters.php](/code/files/api/knowledge/gap_clusters.php.md)

