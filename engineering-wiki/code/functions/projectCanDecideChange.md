# projectCanDecideChange()

May this analyst approve or reject a change request? The setting says who (team = anyone who may change the project; owner = its project manager or Manage Projects; managers = Manage Projects only), and project_change_self whether the person who raised it may decide it too.

```php
function projectCanDecideChange(
    PDO $conn,
    int $analystId,
    array $project,
    ?int $raisedBy = null
): bool
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 238.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - owner_analyst_id is read |
| `$raisedBy` | `?int` | `null` | the analyst who raised the change request; null when unknown or not asking about one |

**Returns:** `bool`

## Calls

[projectCanChange()](/code/functions/projectCanChange.md), [projectIsManager()](/code/functions/projectIsManager.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectControlDetail()](/code/functions/projectControlDetail.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectNudgeDeciders()](/code/functions/projectNudgeDeciders.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::decideChangeRequest`

