# includes/workflow_scheduled.php

> PHP - 287 lines

Time-based workflow triggers.

Every other trigger in the engine hangs off a WRITE PATH: someone saved a
ticket, submitted a form, approved a change — so there is a moment to call
`dispatch()` from. These ones don't. "The SLA is about to breach" and "this
contract expires in 30 days" are not events at all: nothing happened, TIME
PASSED. There is nothing to hang a `dispatch()` off, so something has to go
LOOKING.

That creates a problem the write-path triggers never have: the detector runs
every few minutes, and the condition it detects STAYS TRUE. A breached SLA is
still breached on the next run, and the one after that. Naively dispatching
on detection would re-fire the same escalation every few minutes, forever —
which would be worse than no feature at all.

So the heart of this file is not the detectors. It's `workflowEmitOnce()`:
an atomic, database-backed ledger that guarantees a given (event, thing,
state) fires exactly once, even if two cron runs overlap.

## Functions

| Function | Line | What it does |
|---|--:|---|
| [workflowEmitOnce()](/code/functions/workflowEmitOnce.md) | 54 | Dispatch a time-based event — but only the FIRST time this exact situation is seen. Returns true if it fired, false if it had already fired. |
| [workflowExpiryWindows()](/code/functions/workflowExpiryWindows.md) | 110 | The windows we announce an expiry at. A contract expiring in 45 days crosses the 90-day window today and the 30-day window in a fortnight — two separate emissions, each carrying `window_days`, so a workflow can say "only tell me at 30 days" with a plain condition rather than needing its own scheduling. |
| [workflowEmitContractExpiries()](/code/functions/workflowEmitContractExpiries.md) | 118 | Contracts approaching their end date → `contract.expiring`. |
| [workflowEmitWarrantyExpiries()](/code/functions/workflowEmitWarrantyExpiries.md) | 188 | Asset warranties approaching expiry → `asset.warranty_expiring`. |
| [workflowScheduledRun()](/code/functions/workflowScheduledRun.md) | 237 | Everything the scheduled-trigger cron runs. SLA events are NOT here — they're emitted from `sla_run_breach_check()`, which already walks every open ticket and computes its SLA state, so emitting from there costs nothing and cannot drift from what the SLA emails think is true. |
| [workflowPruneEmissions()](/code/functions/workflowPruneEmissions.md) | 274 | Drop ledger rows for events long past. Keeping them forever would grow without bound; a year is far longer than any window we announce, so nothing can be resurrected by a prune. |

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 68 | `workflowEmitOnce` | read [workflows](/code/tables/workflows.md) |  |
| 79 | `workflowEmitOnce` | **insert** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) (`trigger_event`, `entity_key`, `fingerprint`, `emitted_datetime`) |  |
| 135 | `workflowEmitContractExpiries` | read [contracts](/code/tables/contracts.md), read [suppliers](/code/tables/suppliers.md) |  |
| 197 | `workflowEmitWarrantyExpiries` | read [assets](/code/tables/assets.md) |  |
| 278 | `workflowPruneEmissions` | **delete** [workflow_scheduled_emissions](/code/tables/workflow_scheduled_emissions.md) |  |

## How it hangs together

**Includes:** [workflow/includes/engine.php](/code/files/workflow/includes/engine.php.md), [includes/contract_party.php](/code/files/includes/contract_party.php.md), [includes/timezone.php](/code/files/includes/timezone.php.md), [includes/service_status_planned.php](/code/files/includes/service_status_planned.php.md), [includes/projects/alerts.php](/code/files/includes/projects/alerts.php.md)

**Included by:** [cron/workflow_scheduled.php](/code/files/cron/workflow_scheduled.php.md), [includes/sla_notifications.php](/code/files/includes/sla_notifications.php.md)

**Calls:** [contractPartyLabel()](/code/functions/contractPartyLabel.md), [contractPartySql()](/code/functions/contractPartySql.md), [naive_today_sql()](/code/functions/naive_today_sql.md), [projectAlertsRun()](/code/functions/projectAlertsRun.md), [statusPlannedRun()](/code/functions/statusPlannedRun.md)

