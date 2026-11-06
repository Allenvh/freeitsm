# statusPlannedFinish()

Finishes a planned maintenance: resolves its incident with the first resolved status and backdates the end to the planned end.

Does nothing if the incident is gone, already resolved, or no resolved status exists.

```php
function statusPlannedFinish(PDO $conn, array $p): void
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 323.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$p` | `array` |  | the status_planned row being finished, with its incident_id and planned_end_datetime |

**Returns:** `void`

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 327 | `statusPlannedFinish` | read [status_incidents](/code/tables/status_incidents.md), read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 332 | `statusPlannedFinish` | read [service_incident_statuses](/code/tables/service_incident_statuses.md) |  |
| 340 | `statusPlannedFinish` | **update** [status_incidents](/code/tables/status_incidents.md) (`resolved_datetime`) |  |
| 342 | `statusPlannedFinish` | **update** [status_incident_updates](/code/tables/status_incident_updates.md) (`created_datetime`) |  |

## Calls

[statusPlannedActor()](/code/functions/statusPlannedActor.md)

## Called from

- [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) - in [statusPlannedRun()](/code/functions/statusPlannedRun.md)

