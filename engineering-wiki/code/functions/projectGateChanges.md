# projectGateChanges()

The project's linked changes with whether each is approved - what a change item may point at.

```php
function projectGateChanges(PDO $conn, int $projectId): array
```

Defined in [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md), line 53.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 56 | `projectGateChanges` | read [project_changes](/code/tables/project_changes.md), read [changes](/code/tables/changes.md) |  |

## Called from

- [api/projects/get.php](/code/files/api/projects/get.php.md)
- [includes/projects/gatecheck.php](/code/files/includes/projects/gatecheck.php.md) - in [projectGateItems()](/code/functions/projectGateItems.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::saveGateItem`

