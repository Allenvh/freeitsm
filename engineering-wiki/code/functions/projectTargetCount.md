# projectTargetCount()

[done, total] for one target.

```php
function projectTargetCount(PDO $conn, array $project, array $t): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 158.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - id and tenant_id are read |
| `$t` | `array` |  | the target (a project_asset_targets row) |

**Returns:** `array`

## Calls

[projectTargetSql()](/code/functions/projectTargetSql.md)

## Called from

- [api/projects/tools.php](/code/files/api/projects/tools.php.md)
- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetsFor()](/code/functions/projectTargetsFor.md)

