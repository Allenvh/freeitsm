# projectVisibilityReady()

Has Database Verification added projects.visibility? Before it, everything is visible.

```php
function projectVisibilityReady(PDO $conn): bool
```

Defined in [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md), line 40.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 44 | `projectVisibilityReady` | read [projects](/code/tables/projects.md) |  |

## Called from

- [api/projects/lookups.php](/code/files/api/projects/lookups.php.md)
- [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md) - in [projectSyncCalendar()](/code/functions/projectSyncCalendar.md)
- [includes/projects/visibility.php](/code/files/includes/projects/visibility.php.md) - in [projectVisibilityColumn()](/code/functions/projectVisibilityColumn.md), [projectVisibleSql()](/code/functions/projectVisibleSql.md), [projectIdVisibleTo()](/code/functions/projectIdVisibleTo.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::createProject`

