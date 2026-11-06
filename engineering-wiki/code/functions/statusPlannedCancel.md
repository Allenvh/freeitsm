# statusPlannedCancel()

Cancel planned maintenance that has not started.

```php
function statusPlannedCancel(PDO $conn, ActorContext $ctx, int $id): void
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 130.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$ctx` | `ActorContext` |  | Who is acting, which companies they may reach, ui or api, and their language. *([standard](/handbook/standard-parameters.md))* |
| `$id` | `int` |  | the status_planned row to cancel; must still be 'scheduled' |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 134 | `statusPlannedCancel` | **update** [status_planned](/code/tables/status_planned.md) (`state`, `updated_datetime`) |  |

## Calls

[statusPlannedLoad()](/code/functions/statusPlannedLoad.md)

## Called from

- [api/service-status/planned.php](/code/files/api/service-status/planned.php.md)
- [includes/services/project_tools.php](/code/files/includes/services/project_tools.php.md) - in `ProjectToolsService::withdrawAnnouncement`

