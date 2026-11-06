# rpProjectsRisks()

The open risks that matter most, across the chosen projects.

```php
function rpProjectsRisks(PDO $conn, int $analystId, array $o, array $range, $tenant): array
```

Defined in [includes/report_packs/blocks_projects.php](/code/files/includes/report_packs/blocks_projects.php.md), line 237.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$o` | `array` |  | the block's cleaned options: projects, limit, plans (show response plans) |
| `$range` | `array` |  | the pack's range (not used - open risks now) |
| `$tenant` |  |  | the pack's company: 'active', 'all' or a company id |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 243 | `rpProjectsRisks` | read [project_raid](/code/tables/project_raid.md), read [projects](/code/tables/projects.md), read [analysts](/code/tables/analysts.md) |  |

## Calls

[projectCode()](/code/functions/projectCode.md), [projectScaleLabels()](/code/functions/projectScaleLabels.md), [rpProjectClause()](/code/functions/rpProjectClause.md), [t()](/code/functions/t.md)

*Nothing calls this function by name.*

