# projectTargetsFor()

Every target on these projects with its counts and health: [project_id => [target, ...]]. Empty before Verification.

```php
function projectTargetsFor(PDO $conn, array $projectIds): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 194.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectIds` | `array` |  | the project ids to read targets for |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 199 | `projectTargetsFor` | read [project_asset_targets](/code/tables/project_asset_targets.md), read [projects](/code/tables/projects.md), read [asset_types](/code/tables/asset_types.md) |  |

## Calls

[projectTargetCount()](/code/functions/projectTargetCount.md), [projectTargetHealth()](/code/functions/projectTargetHealth.md), [projectTargetsReady()](/code/functions/projectTargetsReady.md)

## Called from

- [includes/projects/read.php](/code/files/includes/projects/read.php.md) - in [projectTaskStats()](/code/functions/projectTaskStats.md)
- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetsDetail()](/code/functions/projectTargetsDetail.md)

