# projectControlReady()

Has Database Verification created the tables? Everything here is quiet before it.

```php
function projectControlReady(PDO $conn): bool
```

Defined in [includes/projects/control.php](/code/files/includes/projects/control.php.md), line 39.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 43 | `projectControlReady` | read [project_baselines](/code/tables/project_baselines.md) |  |
| 43 | `projectControlReady` | read [project_change_requests](/code/tables/project_change_requests.md) |  |

## Called from

- [includes/projects/control.php](/code/files/includes/projects/control.php.md) - in [projectBaselineAuto()](/code/functions/projectBaselineAuto.md), [projectChangeStats()](/code/functions/projectChangeStats.md), [projectControlDetail()](/code/functions/projectControlDetail.md)
- [includes/projects/nudges.php](/code/files/includes/projects/nudges.php.md) - in [projectAlertsStalled()](/code/functions/projectAlertsStalled.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::takeBaseline`, `ProjectToolsService::saveChangeRequest`

