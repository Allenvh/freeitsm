# projectTargetOptions()

The lists the target dialog offers, for the project's company.

```php
function projectTargetOptions(PDO $conn, array $project): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 310.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - tenant_id picks the company's lists |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 317 | `projectTargetOptions` | read [asset_types](/code/tables/asset_types.md) |  |
| 318 | `projectTargetOptions` | read [asset_status_types](/code/tables/asset_status_types.md) |  |
| 319 | `projectTargetOptions` | read [asset_locations](/code/tables/asset_locations.md) |  |

## Calls

[getDefaultTenantId()](/code/functions/getDefaultTenantId.md), [projectTargetDoneFields()](/code/functions/projectTargetDoneFields.md), [projectTargetScopeFields()](/code/functions/projectTargetScopeFields.md)

## Called from

- [api/projects/tools.php](/code/files/api/projects/tools.php.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveTarget`

