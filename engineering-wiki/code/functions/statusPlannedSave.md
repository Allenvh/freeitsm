# statusPlannedSave()

Schedule (no id) or change (id, while still scheduled) planned maintenance. `$in`: title, comment, start, end?, services [{service_id, impact_level_id}], project_id?. A start that has already passed starts it at once. Returns the id.

```php
function statusPlannedSave(PDO $conn, ActorContext $ctx, array $in): int
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 82.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$in` | `array` |  | the request fields: id (0 or absent = new), title, comment, start, end, services, project_id |

**Returns:** `int`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 104 | `statusPlannedSave` | **update** [status_planned](/code/tables/status_planned.md) (`title`, `comment`, `planned_start_datetime`, `planned_end_datetime`, `updated_datetime`) |  |
| 106 | `statusPlannedSave` | **delete** [status_planned_services](/code/tables/status_planned_services.md) |  |
| 108 | `statusPlannedSave` | **insert** [status_planned](/code/tables/status_planned.md) (`title`, `comment`, `planned_start_datetime`, `planned_end_datetime`, `state`, `project_id`, `created_by_id`, `created_datetime`, `updated_datetime`) |  |
| 113 | `statusPlannedSave` | **insert** [status_planned_services](/code/tables/status_planned_services.md) (`planned_id`, `service_id`, `impact_level_id`) |  |

## Calls

[statusPlannedLoad()](/code/functions/statusPlannedLoad.md), [statusPlannedReady()](/code/functions/statusPlannedReady.md), [statusPlannedRun()](/code/functions/statusPlannedRun.md), [statusPlannedUtc()](/code/functions/statusPlannedUtc.md)

## Called from

- [api/service-status/planned.php](/code/files/api/service-status/planned.php.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::announce`

