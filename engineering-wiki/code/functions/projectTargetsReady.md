# projectTargetsReady()

Has Database Verification created the tables?

```php
function projectTargetsReady(PDO $conn): bool
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 31.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 35 | `projectTargetsReady` | read [project_asset_targets](/code/tables/project_asset_targets.md) |  |
| 35 | `projectTargetsReady` | read [project_asset_target_snapshots](/code/tables/project_asset_target_snapshots.md) |  |

## Called from

- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetsFor()](/code/functions/projectTargetsFor.md)
- [includes/projects/templates.php](/code/files/includes/projects/templates.php.md) - in [projectTemplateCapture()](/code/functions/projectTemplateCapture.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveTarget`

