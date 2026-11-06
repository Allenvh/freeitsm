# rpProjectsKpis()

Projects block: KPI tiles - live projects, how many are green, amber and red now, and stages ending in the period.

```php
function rpProjectsKpis(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 94.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options (none are read) |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - for stages ending in the period |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 101 | `rpProjectsKpis` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |

## Calls

[rpProjectClause()](/code/functions/rpProjectClause.md), [rpProjectRows()](/code/functions/rpProjectRows.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

