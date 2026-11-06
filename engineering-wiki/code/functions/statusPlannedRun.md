# statusPlannedRun()

Start what is due and finish what has ended. `$onlyId` = one plan (just saved).

```php
function statusPlannedRun(PDO $conn, ?int $onlyId = null): array
```

Defined in [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), line 219.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |
| `$onlyId` | `?int` | `null` | a status_planned id to act on alone, or null for every plan that is due |

**Returns:** array{started:int, finished:int}

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 225 | `statusPlannedRun` | read [status_planned](/code/tables/status_planned.md) |  |
| 228 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`, `updated_datetime`) |  |
| 236 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`) |  |
| 241 | `statusPlannedRun` | read [status_planned](/code/tables/status_planned.md) |  |
| 243 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`, `updated_datetime`) |  |
| 250 | `statusPlannedRun` | **update** [status_planned](/code/tables/status_planned.md) (`state`) |  |

## Calls

[statusPlannedFinish()](/code/functions/statusPlannedFinish.md), [statusPlannedReady()](/code/functions/statusPlannedReady.md), [statusPlannedStart()](/code/functions/statusPlannedStart.md)

## Called from

- [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md) - in [statusPlannedSave()](/code/functions/statusPlannedSave.md), [statusPlannedDue()](/code/functions/statusPlannedDue.md)
- [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md) - in [workflowScheduledRun()](/code/functions/workflowScheduledRun.md)

