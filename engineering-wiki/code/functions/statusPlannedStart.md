# statusPlannedStart()

Starts a planned maintenance: opens an external incident on its services and backdates it to the planned start.

```php
function statusPlannedStart(PDO $conn, array $p): void
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 294.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$p` | `array` |  | the status_planned row being started, as read by `statusPlannedRun()` |

**Returns:** `void`

**Writes:** status_incidents, status_incident_updates(created_datetime), status_planned(incident_id)

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 296 | `statusPlannedStart` | read [status_planned_services](/code/tables/status_planned_services.md) |  |
| 309 | `statusPlannedStart` | **update** [status_incidents](/code/tables/status_incidents.md) (`created_datetime`) |  |
| 310 | `statusPlannedStart` | **update** [status_incident_updates](/code/tables/status_incident_updates.md) (`created_datetime`) |  |
| 312 | `statusPlannedStart` | **update** [status_planned](/code/tables/status_planned.md) (`incident_id`) |  |

## Calls

[statusPlannedActor()](/code/functions/statusPlannedActor.md), [statusPlannedStatusId()](/code/functions/statusPlannedStatusId.md)

## Called from

- [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) - in [statusPlannedRun()](/code/functions/statusPlannedRun.md)

