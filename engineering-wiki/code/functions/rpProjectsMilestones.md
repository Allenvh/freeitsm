# rpProjectsMilestones()

Stage ends and target finishes inside the range, and whether each was met.

```php
function rpProjectsMilestones(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 184.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: projects (ids; [] for all) |
| `$range` | `array` |  | the pack's range from `rpResolveRange()` - from_date and to_date are used |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 192 | `rpProjectsMilestones` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 200 | `rpProjectsMilestones` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |

## Calls

[projectCode()](/code/functions/projectCode.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [rpFmtDate()](/code/functions/rpFmtDate.md), [rpProjectClause()](/code/functions/rpProjectClause.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

