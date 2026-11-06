# projectSyncCalendar()

Redraw both kinds. Never throws: a calendar that is out of step must never stop a project being saved.

```php
function projectSyncCalendar(PDO $conn): array
```

Defined in [includes/projects/calendar.php](/code/files/includes/projects/calendar.php.md), line 46.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 60 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |
| 64 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |
| 68 | `projectSyncCalendar` | read [project_stages](/code/tables/project_stages.md), read [projects](/code/tables/projects.md) |  |
| 73 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |
| 81 | `projectSyncCalendar` | read [project_milestones](/code/tables/project_milestones.md), read [projects](/code/tables/projects.md) |  |
| 86 | `projectSyncCalendar` | read [projects](/code/tables/projects.md) |  |

## Calls

[awcColumnExists()](/code/functions/awcColumnExists.md), [projectCode()](/code/functions/projectCode.md), [projectMilestonesReady()](/code/functions/projectMilestonesReady.md), [projectSettings()](/code/functions/projectSettings.md), [projectSyncCalendarKind()](/code/functions/projectSyncCalendarKind.md), [projectVisibilityReady()](/code/functions/projectVisibilityReady.md)

## Called from

- [api/projects/settings.php](/code/files/api/projects/settings.php.md)
- [includes/services/projects.php](/code/files/includes/services/projects.php.md) - in `ProjectsService::syncCalendar`

