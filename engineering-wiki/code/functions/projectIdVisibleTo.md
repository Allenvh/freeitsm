# projectIdVisibleTo()

The same, by id - for callers holding only an id (Documents, the recent trail).

```php
function projectIdVisibleTo(PDO $conn, int $analystId, int $projectId): bool
```

Defined in [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), line 100.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$analystId` | `int` |  | The analyst doing this - normally the one signed in. *([standard](/handbook/standard-parameters.md))* |
| `$projectId` | `int` |  | The id of the project. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 103 | `projectIdVisibleTo` | read [projects](/code/tables/projects.md) |  |

## Calls

[projectVisibilityReady()](/code/functions/projectVisibilityReady.md), [projectVisibleTo()](/code/functions/projectVisibleTo.md)

## Called from

- [includes/recent_trail.php](/code/files/includes/recent_trail.php.md) - in [recentTrailLabelsForType()](/code/functions/recentTrailLabelsForType.md)
- [includes/tenancy.php](/code/files/includes/tenancy.php.md) - in [analystCanAccessProject()](/code/functions/analystCanAccessProject.md)

