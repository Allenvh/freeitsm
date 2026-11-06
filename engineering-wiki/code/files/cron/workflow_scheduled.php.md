# cron/workflow_scheduled.php

> PHP - 99 lines

Time-based workflow triggers — cron entry point.

Every other workflow trigger hangs off a write path: someone saved a ticket,
so there is a moment to dispatch from. These ones don't exist until something
goes looking. "This contract expires in 30 days" is not an event — nothing
happened, TIME PASSED.

Emits:
  contract.expiring        — at 90 / 30 / 7 / 1 days out
  asset.warranty_expiring  — same windows

NOT here: `sla.warning` and `sla.breached`. Those are emitted from
cron/sla_breach_check.php, which already walks every open ticket and computes
its SLA state — so emitting there costs nothing extra and cannot drift from
what the SLA emails believe. Schedule BOTH crons if you want SLA workflows.

Fire-once is guaranteed by the `workflow_scheduled_emissions` ledger (a UNIQUE
key + INSERT IGNORE), not by this script — so overlapping runs are safe, and a
still-true condition (a breached SLA stays breached) cannot re-fire on every
pass. Run it as often as you like; hourly is plenty for expiry windows.

SECURITY (HTTP invocation only), mirroring the other crons:
  - Shared-secret token via ?token=<value> matching `workflow_cron_token`.
  - Min interval between runs, defeating double-scheduling / runaway loops.
CLI invocation skips the token (there is no untrusted caller).

## Database

| Line | In | Tables | Purpose |
|--:|---|---|---|
| 45 |  | read [system_settings](/code/tables/system_settings.md) |  |
| 83 |  | **insert** [system_settings](/code/tables/system_settings.md) (`setting_key`, `setting_value`) |  |

## How it hangs together

**Includes:** [config.php](/code/files/config.php.md), [includes/functions.php](/code/files/includes/functions.php.md), [includes/encryption.php](/code/files/includes/encryption.php.md), [includes/workflow_scheduled.php](/code/files/includes/workflow_scheduled.php.md)

**Calls:** [connectToDatabase()](/code/functions/connectToDatabase.md), [decryptValue()](/code/functions/decryptValue.md), [workflowPruneEmissions()](/code/functions/workflowPruneEmissions.md), [workflowScheduledRun()](/code/functions/workflowScheduledRun.md)

