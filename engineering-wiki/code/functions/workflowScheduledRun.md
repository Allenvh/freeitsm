# workflowScheduledRun()

Everything the scheduled-trigger cron runs. SLA events are NOT here — they're emitted from `sla_run_breach_check()`, which already walks every open ticket and computes its SLA state, so emitting from there costs nothing and cannot drift from what the SLA emails think is true.

```php
function workflowScheduledRun(PDO $conn): array
```

Defined in [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md), line 237.

| Parameter | Type | Default | Meaning |
|---|---|---|---|
| `$conn` | `PDO` |  | The database connection. *([standard](/handbook/standard-parameters.md))* |

**Returns:** `array`

## Calls

[projectAlertsRun()](/code/functions/projectAlertsRun.md), [statusPlannedRun()](/code/functions/statusPlannedRun.md), [workflowEmitContractExpiries()](/code/functions/workflowEmitContractExpiries.md), [workflowEmitWarrantyExpiries()](/code/functions/workflowEmitWarrantyExpiries.md)

## Called from

- [cron/workflow_scheduled.php](/code/files/cron/workflow_scheduled.php.md)

