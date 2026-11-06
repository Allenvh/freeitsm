# statusPlannedDue()

Is anything due to start or end? One indexed query - cheap enough for every page load.

```php
function statusPlannedDue(PDO $conn): void
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 199.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 203 | `statusPlannedDue` | read [status_planned](/code/tables/status_planned.md) |  |

## Calls

[statusPlannedReady()](/code/functions/statusPlannedReady.md), [statusPlannedRun()](/code/functions/statusPlannedRun.md)

## Called from

- [api/self-service/get_dashboard.php](/code/files/api/self-service/get_dashboard.php.md)
- [api/service-status/get_dashboard.php](/code/files/api/service-status/get_dashboard.php.md)
- [api/service-status/planned.php](/code/files/api/service-status/planned.php.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::announcements`
- [includes/watchtower_queries.php](/code/files/includes/watchtower_queries.php.md) - in [getWatchtowerData()](/code/functions/getWatchtowerData.md)

