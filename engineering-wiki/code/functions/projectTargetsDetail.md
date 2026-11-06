# projectTargetsDetail()

For the project page: the targets, with today's point written and the last 120 days of points for the burn-up line, and the words for the rule.

```php
function projectTargetsDetail(PDO $conn, int $projectId): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 240.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 245 | `projectTargetsDetail` | **insert** [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) (`target_id`, `snap_date`, `done`, `total`) |  |
| 247 | `projectTargetsDetail` | read [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) |  |

## Calls

[projectTargetsFor()](/code/functions/projectTargetsFor.md), [projectTargetValueNames()](/code/functions/projectTargetValueNames.md)

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)

