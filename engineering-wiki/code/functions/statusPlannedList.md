# statusPlannedList()

Planned maintenance for a list: scheduled and in progress by default, soonest first, each with its services. `$opts`: project_id, states, limit.

```php
function statusPlannedList(PDO $conn, array $opts = []): array
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 157.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$opts` | `array` | `[]` | project_id (only that project's), states (default scheduled + started), limit (1-500, default 100) |

**Returns:** `array`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 165 | `statusPlannedList` | read [status_planned](/code/tables/status_planned.md), read [analysts](/code/tables/analysts.md), read [projects](/code/tables/projects.md) |  |
| 176 | `statusPlannedList` | read [status_planned_services](/code/tables/status_planned_services.md), read [status_services](/code/tables/status_services.md), read [service_impact_levels](/code/tables/service_impact_levels.md) |  |

## Calls

[statusPlannedReady()](/code/functions/statusPlannedReady.md)

## Called from

- [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md)
- [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md)
- [api/service-status/planned.php](/code/files/api/service-status/planned.php.md)
- [includes/projects/ai.php](/code/files/includes/projects/ai.php.md) - in [projectAiFacts()](/code/functions/projectAiFacts.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::announcements`

