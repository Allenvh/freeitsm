# rpProjectsStatus()

Projects block: a table of projects with health, progress, current stage, target date and, optionally, manager and the reason for the health.

```php
function rpProjectsStatus(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 144.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: projects, scope ('live' or all), notes, manager |
| `$range` | `array` |  | the pack's range (not used - a snapshot of today) |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Calls

[rpFmtDate()](/code/functions/rpFmtDate.md), [rpProjectHealthPill()](/code/functions/rpProjectHealthPill.md), [rpProjectRows()](/code/functions/rpProjectRows.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

