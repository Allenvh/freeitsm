# rpProjectsHealth()

Projects block: a chart of live projects by health (green, amber, red). A snapshot of today.

```php
function rpProjectsHealth(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 121.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: projects (ids; [] for all), chart |
| `$range` | `array` |  | the pack's range (not used - a snapshot of today) |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Calls

[rpProjectRows()](/code/functions/rpProjectRows.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

