# projectCanCreate()

True when the analyst may create projects - anyone, if the create policy says so, else project managers only.

```php
function projectCanCreate(PDO $conn, int $analystId): bool
```

Defined in [includes/projects/settings.php](/code/files/includes/projects/settings.php.md), line 434.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Calls

[projectIsManager()](/code/functions/projectIsManager.md), [projectSetting()](/code/functions/projectSetting.md)

## Called from

- [api/projects/list.php](/code/files/api/projects/list.php.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`

