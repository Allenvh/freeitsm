# projectCanChange()

True when the analyst may change the project - anyone, if the change policy says so, else its team and project managers.

```php
function projectCanChange(PDO $conn, int $analystId, array $project): bool
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 467.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$project` | `array` |  | the projects row - id, owner_analyst_id and created_by_id are read |

**Returns:** `bool`

## Calls

[projectIsManager()](/code/functions/projectIsManager.md), [projectIsTeam()](/code/functions/projectIsTeam.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [api/projects/assistant_chat.php](/code/files/api/projects/assistant_chat.php.md)
- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [api/projects/reports.php](/code/files/api/projects/reports.php.md)
- [includes/projects/assistant_chat.php](/code/files/includes/projects/assistant_chat.php.md) - in [projectChatTurn()](/code/functions/projectChatTurn.md), [projectChatClear()](/code/functions/projectChatClear.md)
- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectCanDecideChange()](/code/functions/projectCanDecideChange.md), [projectControlDetail()](/code/functions/projectControlDetail.md)
- [includes/projects/links.php](/code/files/includes/projects/links.php.md) - in [projectsPickableFor()](/code/functions/projectsPickableFor.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::assertCanChange`

