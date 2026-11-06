# statusPlannedReady()

Has Database Verification created the tables?

```php
function statusPlannedReady(PDO $conn): bool
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 51.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `bool`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 55 | `statusPlannedReady` | read [status_planned](/code/tables/status_planned.md) |  |
| 55 | `statusPlannedReady` | read [status_planned_services](/code/tables/status_planned_services.md) |  |

## Called from

- [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) - in [statusPlannedSave()](/code/functions/statusPlannedSave.md), [statusPlannedList()](/code/functions/statusPlannedList.md), [statusPlannedDue()](/code/functions/statusPlannedDue.md), [statusPlannedRun()](/code/functions/statusPlannedRun.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::announcements`

