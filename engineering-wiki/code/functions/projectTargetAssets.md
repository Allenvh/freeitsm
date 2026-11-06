# projectTargetAssets()

The assets still to do on a target (or all of them, `$done` = true), at most `$limit` - for the "what's left" list. Callers check Assets access first.

```php
function projectTargetAssets(
    PDO $conn,
    array $project,
    array $t,
    bool $doneOnes = false,
    int $limit = 200
): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 288.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - id and tenant_id are read |
| `$t` | `array` |  | the target (a project_asset_targets row) |
| `$doneOnes` | `bool` | `false` | true for the assets already done; false (the default) for those still to do |
| `$limit` | `int` | `200` | the most assets to return |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 297 | `projectTargetAssets` | read [asset_status_types](/code/tables/asset_status_types.md), read [asset_locations](/code/tables/asset_locations.md) |  |

## Calls

[entityLink()](/code/functions/entityLink.md), [projectTargetSql()](/code/functions/projectTargetSql.md)

## Called from

- [api/projects/tools.php](/code/files/api/projects/tools.php.md)

## Traps

> [!WARNING]
> **Line 291, in `projectTargetAssets`:** an asset with no status makes "status = 6" NULL, not false, and NOT(NULL) is NULL too - the asset would vanish from "still to do". The count's CASE WHEN already treats NULL as not done; this must agree.

