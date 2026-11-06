# projectTargetNormalise()

Check and tidy a target from the screen. Returns the row to store; throws ServiceError on anything the SQL builder would not accept.

```php
function projectTargetNormalise(array $in): array
```

Defined in [includes/projects/targets.php](/code/files/includes/projects/targets.php.md), line 71.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$in` | `array` |  | the target as sent by the screen, or a stored project_asset_targets row (untrusted either way) |

**Returns:** `array`

## Calls

[projectTargetDoneFields()](/code/functions/projectTargetDoneFields.md), [projectTargetScopeFields()](/code/functions/projectTargetScopeFields.md)

## Called from

- [includes/projects/targets.php](/code/files/includes/projects/targets.php.md) - in [projectTargetSql()](/code/functions/projectTargetSql.md)
- [includes/services/project_templates.php](/code/files/includes/services/project_templates.php.md) - in `ProjectTemplatesService::createFromTemplate`
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveTarget`

